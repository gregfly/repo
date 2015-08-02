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
            $this->Name = substr_replace($text, $cmd->String, $cmd->CursorBegin, $cmd->CursorEnd - $cmd->CursorBegin + (integer)empty($cmd->String));
            return true;
        }
        return false;
    }
}
