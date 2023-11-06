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
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'custom/css/font-awesome.css',
        'custom/css/bootstrap.css',
        'custom/css/select2.min.css',
        'custom/css/datepicker3.css',
        'custom/css/bootstrap-timepicker.css',
        'custom/css/slick.css',
        'admin/css/jquery-ui.css',
        'admin/css/fonts.css',
        'admin/css/header-mins.css',
        'map/css/map.css',
    ];
    public $js = [
        'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAO5mxd3SQ5Z_cqRlmhAfjUTgFNj_BZrTk&libraries=geometry,places',
        'custom/js/select2.full.min.js',
        'custom/js/bootstrap.js',
        'custom/js/datepicker/bootstrap-datepicker.js',
        'custom/js/bootstrap-timepicker.js',
        'custom/js/slick.js',
        'custom/js/waypoints.js',
        'custom/js/jquery.counterup.js',
        'admin/js/timeout.js',
        'admin/js/modals.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
