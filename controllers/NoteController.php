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
use app\models\Text;

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
    /**
     * @var string 
     */
    public $mutexKey = 'mutex-key';
    
    public function init()
    {
        $this->mutex = Instance::ensure($this->mutex, 'yii\mutex\Mutex');
        $this->db = Instance::ensure($this->db, 'yii\db\Connection');
        $this->request = Instance::ensure($this->request, 'yii\web\Request');
        $this->response = Instance::ensure($this->response, 'yii\web\Response');
        return parent::init();
    }

    public function actionSave()
    {
        $this->response->format = Response::FORMAT_JSON;
        $acts = $this->request->post('acts', []);
        if ($this->mutex->acquire($this->mutexKey)) {
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
            $this->mutex->release($this->mutexKey);
        }
        return [];
    }
    
    public function actionBind()
    {
        $this->response->format = Response::FORMAT_JSON;
        $tid = $this->request->post('tid');
        $token = $this->request->post('token');
        $reqs = $this->request->post('reqs', []);
        $cmds = [];
        if ($this->mutex->acquire($this->mutexKey)) {
            $noteIds = [];
            $where = [];
            foreach ($reqs as $data) {
                $noteIds[$data['ID']] = $data['ID'];
                array_push($where, '(NoteID = '.$data['ID'].' AND ID > '.$data['NoteActionID'].' AND UserID != \''.$token.'\')');
            }
            $cmds = NoteAction::find()
                    ->andWhere(implode(' OR ', $where))
                    ->orderBy(['Timestamp' => SORT_ASC])
                    ->all();
            /* @var $notes Note[] */
            $notes = Note::find()
                    ->distinct()
                    ->joinWith(['paragraph'], false)
                    ->andWhere(['TextID' => $tid])
                    ->all();
            foreach ($notes as $note) {
                if (!isset($noteIds[$note->ID])) {
                    array_push($cmds, [
                        'ID' => 0,
                        'NoteID' => $note->ID,
                        'String' => $note->Name,
                        'ParagraphID' => $note->ParagraphID,
                        'Type' => NoteAction::MODE_C,
                    ]);
                }
                unset($noteIds[$note->ID]);
            }
            foreach ($noteIds as $noteId) {
                array_push($cmds, [
                    'NoteID' => $noteId,
                    'Type' => NoteAction::MODE_R,
                ]);
            }
            $this->mutex->release($this->mutexKey);
        }
        return [
            'cmds' => $cmds,
        ];
    }
    
    public function actionCreate()
    {
        $model = new Note();
        if ($model->load($this->request->post(), '') && $model->save()) {
            if ($this->request->isAjax) {
                $this->response->format = Response::FORMAT_JSON;
                return $model->toArray([], ['NoteActionID']);
            }
        }
        return $this->redirect('index');
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        if ($this->request->isAjax) {
            $this->response->format = Response::FORMAT_JSON;
            return true;
        }
        return $this->redirect('index');
    }
    
    public function findModel($id)
    {
        if (!is_null($model = Note::findOne($id))) {
            return $model;
        }
        throw new HttpException(404, 'Page not found');
    }
}
