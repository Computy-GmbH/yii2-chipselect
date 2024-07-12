<?php

namespace computy\chipselect;

use yii\web\AssetBundle;

class ChipSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $css      = [
        'css/chip-select.css',
    ];
    public $js       = [
        'js/chip-select.js',
    ];
}