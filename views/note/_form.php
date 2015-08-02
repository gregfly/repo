<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Note */

echo Html::beginTag('div', ['class' => 'note-form']);

$form = ActiveForm::begin([
    'options' => [
        'data' => [
            'key' => $model->ID,
        ],
    ],
]);

echo $form->field($model, 'Name', ['template' => '{input}'])->textarea();

ActiveForm::end();

echo Html::endTag('div');