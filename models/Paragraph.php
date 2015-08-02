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
 * Description of Paragraph
 *
 * @author Volkov Grigorii
 * @property integer $ID
 * @property integer $TextID
 * @property double $Position
 * @property string $Name
 * 
 * @property-read array $notesData
 * 
 * @property Note[] $notes
 */
class Paragraph extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%paragraph}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TextID', 'Position', 'Name'], 'required'],
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
            'TextID' => Yii::t('app', 'TextID'),
            'Position' => Yii::t('app', 'Position'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotes()
    {
        return $this->hasMany(Note::className(), ['ParagraphID' => 'ID']);
    }
    
    /**
     * @return array
     */
    public function getNotesData()
    {
        $output = [];
        foreach ($this->notes as $note) {
            array_push($output, $note->toArray([], ['NoteActionID']));
        }
        return $output;
    }
}
