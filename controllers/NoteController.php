<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\di\Instance;
use yii\web\Response;
use app\models\Note;
use app\models\NoteAction;
use app\base\Mutex;

/**
 * Description of NoteController
 *
 * @author Volkov Grigorii
 */
class NoteController extends Controller
{
    /**
     * @var \yii\mutex\Mutex
     */
    public $mutex = 'mutex';
    /**
     * @var \yii\db\Connection
     */
    public $db = 'db';
    /**
     * @var \yii\web\Request
     */
    public $request = 'request';
    /**
     * @var \yii\web\Response
     */
    public $response = 'response';
    
    public function init()
    {
        $this->mutex = Instance::ensure($this->mutex, 'yii\mutex\Mutex');
        $this->db = Instance::ensure($this->db, 'yii\db\Connection');
        $this->request = Instance::ensure($this->request, 'yii\web\Request');
        $this->response = Instance::ensure($this->response, 'yii\web\Response');
        return parent::init();
    }

    public function actionSave(array $acts)
    {
        $this->response->format = Response::FORMAT_JSON;
        $key = 'save-note';
        $mutex = new Mutex([
            'id' => $key,
        ]);
//        if ($mutex->acquire()) {
        if ($this->mutex->acquire($key)) {
            $tr = $this->db->beginTransaction();
            try {
                $noteIds = [];
                $cmds = [];
                foreach ($acts as $data) {
                    $cmd = new NoteAction();
                    if ($cmd->load($data, '') && $cmd->validate()) {
                        array_push($noteIds, $cmd->NoteID);
                        array_push($cmds, $cmd);
                    }
                }
                /* @var $notes Note[] */
                $notes = Note::find()
                        ->andWhere(['ID' => $noteIds])
                        ->indexBy('ID')
                        ->all();
                foreach ($cmds as $k => $cmd) {
                    if (!isset($notes[$cmd->NoteID]) || !$notes[$cmd->NoteID]->patch($cmd)) {
                        $cmds[$k] = false; // command incorrect
                    }
                }
                foreach ($cmds as $cmd) {
                    if (!$cmd) {
                        continue;
                    }
                    $cmd->save();
                }
                foreach ($notes as $note) {
                    $note->save();
                }
                $tr->commit();
            } catch (\Exception $ex) {
                $tr->rollBack();
            }
            $this->mutex->release($key);
        }
//            $mutex->release();
//        }
        return [
            'cmds' => [],
        ];
    }
    
    public function actionTest()
    {
        $note = $this->findModel(1);
        var_dump($note->Name);
        $patch = new NoteAction();
        $patch->NoteID = $note->ID;
        $patch->CursorBegin = 12;
        $patch->CursorEnd = 12;
        $patch->String = 'п';
        $patch->Type = NoteAction::MODE_W;
        $note->patch($patch);
        $patch->CursorBegin = 13;
        $patch->CursorEnd = 13;
        $patch->String = 'р';
        $note->patch($patch);
        $patch->CursorBegin = 14;
        $patch->CursorEnd = 14;
        $patch->String = 'и';
        $note->patch($patch);
        var_dump($note->Name);
        die;
    }
    
    public function findModel($id)
    {
        if (!is_null($model = Note::findOne($id))) {
            return $model;
        }
        throw new HttpException(404, 'Page not found');
    }
}
