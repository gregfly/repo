<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\base;

use yii\web\AssetBundle;

/**
 * Description of TextViewAsset
 *
 * @author Volkov Grigorii
 */
class TextViewAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
        'css/text_view.css',
    ];
    public $js = [
        'js/common.js',
        'js/text_view.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\angularjs\AngularAsset',
    ];
}
