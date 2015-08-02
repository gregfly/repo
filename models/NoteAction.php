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
 * Description of NoteAction
 *
 * @author Volkov Grigorii
 * @property integer $ID
 * @property string $UserID
 * @property integer $NoteID
 * @property integer $Timestamp
 * @property string $Type
 * @property integer $CursorBegin
 * @property integer $CursorEnd
 * @property string $String
 * 
 * @property Note $note
 */
class NoteAction extends ActiveRecord
{
    const MODE_W = 'w';
    const MODE_R = 'r';
    const MODE_D = 'd';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%note_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserID', 'NoteID', 'Timestamp', 'Type', 'CursorBegin', 'CursorEnd'], 'required'],
            [['UserID', 'Type', 'String'], 'string'],
            [['String'], 'required', 'skipOnEmpty' => true, 'strict' => true],
            [['UserID', 'NoteID', 'Timestamp', 'Type', 'CursorBegin', 'CursorEnd', 'String'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'UserID' => Yii::t('app', 'UserID'),
            'NoteID' => Yii::t('app', 'NoteID'),
            'Timestamp' => Yii::t('app', 'Timestamp'),
            'Type' => Yii::t('app', 'Type'),
            'CursorBegin' => Yii::t('app', 'CursorBegin'),
            'CursorEnd' => Yii::t('app', 'CursorEnd'),
            'String' => Yii::t('app', 'String'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParagraph()
    {
        return $this->hasOne(Paragraph::className(), ['ID' => 'ParagraphID']);
    }
}
