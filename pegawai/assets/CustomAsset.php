<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'custom/css/font-awesome.css',
        'custom/css/bootstrap.css',
        'custom/css/select2.min.css',
        'css/lte/AdminLTE.min.css',
        'css/lte/_all-skins.css',
        'css/site.css',
        'custom/css/slick.css',
        'custom/css/theme-color/default-theme.css',
        'custom/css/style.css',
        'https://fonts.googleapis.com/css?family=Montserrat:400,700',
        'https://fonts.googleapis.com/css?family=Roboto:400,400italic,300,300italic,500,700',
    ];
    public $js = [
        'custom/js/jquery.min.js',
        'custom/js/select2.full.min.js',
        'custom/js/bootstrap.js',
        'custom/js/slick.js',
        'custom/js/waypoints.js',
        'custom/js/jquery.counterup.js',
        'custom/js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
