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
    'ng-init' => "token = '{$token}'",
]);

echo Html::beginTag('table');
echo Html::beginTag('tbody');
foreach ($model->paragraphs as $p) {
    echo Html::beginTag('tr', [
        'data' => ['key' => $p->ID],
    ]);
    echo Html::tag('td', $p->Name);
    echo Html::beginTag('td', [
        'ng-init' => "notes['{$p->ID}'] = ".Json::encode($p->notes),
    ]);
    echo Html::beginTag('div', [
        'class' => 'note-form ng-hide',
        'ng-repeat' => "note in notes['{$p->ID}']",
        'ng-show' => '1',
    ]);
    echo Html::textarea('Note[{{ note.ID }}]', '{{ note.Name }}', [
        'ng-keydown' => 'noteKeyDown(note, $event)',
        'id' => 'note-{{ note.ID }}'
    ]);
    echo Html::endTag('div');
    echo Html::endTag('td');
    echo Html::endTag('tr');
}
echo Html::endTag('tbody');
echo Html::endTag('table');

echo Html::endTag('div');