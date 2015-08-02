<?php
use yii\helpers\Html;
use app\base\TextViewAsset;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Text */
/* @var $token string */

TextViewAsset::register($this);

$this->title = $model->Name;

echo Html::beginTag('div', [
    'class' => 'text-view',
    'ng-app' => 'notes',
    'ng-controller' => 'notesController',
    'ng-init' => "token = '{$token}'; textId = {$model->ID};",
]);

echo Html::beginTag('table');
echo Html::beginTag('tbody');
foreach ($model->paragraphs as $p) {
    echo Html::beginTag('tr', [
        'data' => ['key' => $p->ID],
    ]);
    echo Html::tag('td', $p->Name);
    echo Html::beginTag('td', [
        'ng-init' => "notes['{$p->ID}'] = ".Json::encode($p->notesData),
    ]);
    echo Html::beginTag('div', [
        'class' => 'note-form ng-hide',
        'ng-repeat' => "note in notes['{$p->ID}']",
        'ng-show' => '1',
    ]);
    echo Html::tag('div', Html::a(Yii::t('app', 'Remove'), 'javascript:void(0);', [
        'ng-click' => "deleteNote(note)",
    ]));
    echo Html::textarea('Note[{{ note.ID }}]', '', [
        'id' => 'note-{{ note.ID }}',
        'ng-model' => 'note.Name',
        'ng-keydown' => 'noteKeyDown(note, $event)',
    ]);
    echo Html::endTag('div');
    echo Html::a(Yii::t('app', 'Add'), 'javascript:void(0);', [
        'ng-click' => "createNote('{$p->ID}')",
    ]);
    echo Html::endTag('td');
    echo Html::endTag('tr');
}
echo Html::endTag('tbody');
echo Html::endTag('table');

echo Html::endTag('div');