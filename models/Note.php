<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Description of Note
 *
 * @author Volkov Grigorii
 * @property integer $ID
 * @property integer $ParagraphID
 * @property string $Name
 * 
 * @property Paragraph $paragraph
 * @property NoteAction[] $noteActions
 */
class Note extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%note}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ParagraphID'], 'required'],
            [['Name'], 'required', 'skipOnEmpty' => true, 'strict' => true],
            [['Name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'ParagraphID' => Yii::t('app', 'ParagraphID'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParagraph()
    {
        return $this->hasOne(Paragraph::className(), ['ID' => 'ParagraphID']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoteActions()
    {
        return $this->hasMany(NoteAction::className(), ['NoteID' => 'ID']);
    }
    
    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'NoteActionID' => function(Note $m) {
                return $m->getNoteActions()->max('ID')? : 0;
            },
        ];
    }
    
    protected function utf8_substr_replace($str, $repl, $start, $length = null)
    {
        preg_match_all('/./us', $str, $ar);
        preg_match_all('/./us', $repl, $rar);
        $length = is_int($length) ? $length : utf8_strlen($str);
        array_splice($ar[0], $start, $length, $rar[0]);
        return implode($ar[0]);
    }
    
    /**
     * @param \app\models\NoteAction $cmd
     * @return boolean
     */
    public function patch(NoteAction $cmd)
    {
        if ($this->ID != $cmd->NoteID) {
            return false;
        }
        $text = $this->Name;
        if (NoteAction::MODE_W == $cmd->Type) {
            $this->Name = $this->utf8_substr_replace($text, $cmd->String, $cmd->CursorBegin, $cmd->CursorEnd - $cmd->CursorBegin + (integer)empty($cmd->String));
            return true;
        }
        return false;
    }
}
