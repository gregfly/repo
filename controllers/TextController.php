<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Text;
use yii\web\HttpException;
use app\base\Helper;

/**
 * Description of TextController
 *
 * @author Volkov Grigorii
 */
class TextController extends Controller
{
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'token' => Helper::token(20),
        ]);
    }
    
    public function findModel($id)
    {
        if (!is_null($model = Text::findOne($id))) {
            return $model;
        }
        throw new HttpException(404, 'Page not found');
    }
}
