<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'bower_components/font-awesome/css/font-awesome.min.css',
        'bower_components/Ionicons/css/ionicons.min.css',
        'dist/css/AdminLTE.css',
        'dist/css/skins/_all-skins.css',
        'css/toastr.min.css',
    ];
    public $js = [
        'bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        'dist/js/adminlte.js',
        'dist/js/demo.js',
        'dist/js/toastr.min.js',
        'dist/js/jquery.blockUI.js',
        'js/action.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}