<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAsset;
use common\widgets\Alert;

$asset = AdminAsset::register($this);
$baseUrl = $asset->baseUrl;

$time = new \DateTime('now', new \DateTimeZone(TIMEZONE));
$now = $time->format('D, D-m-Y H:i:s');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <title>SIKKEPO BKD PROVINSI PAPUA BARAT</title>
  <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<?php $this->beginBody() ?>
  <?php if (!Yii::$app->user->isGuest) : ?>
  
  <nav id="navbar-fixed" class="navbar navbar-static-top">
    <div id="progress-loader" style="display: none; z-index: 10; width: 100%; height: 100%; position: fixed; background-color: rgba(255, 255, 255, 0.7); color: #fff;"><div style="position: relative; width: 10%; top: 40%; left: 45%; right: 45%; text-align: center"><img style="width: 60%" src="admin/img/double-ring.svg" /></div></div>
    <div class="header">
      <div class="header-content">
        <div class="navbar-brand">
          <img style="height: 50px; width: 40px" src="<?= L ?>" />
        </div>
        <span style="font-size: 3em; color: #fff"><?= T ?></span><span style="font-size: 1.5em; color: #fff; margin-left: 8px"><?= K ?></span>
        <div id="nav-logout">
          <?= Html::a('<div class="navbar-right-logout" ><div class="logout-icon"><i class="fa fa-sign-out"></i></div><div class="logout-text">Logout</div></div>', ['site/logout'], ['data'=>['method'=>'post', 'confirm'=>'Logout?']]) ?>
        </div>
        <div id="usermenu">
          <a class="dropdown dropdown-toggle" aria-expanded="false" role="button" data-toggle="dropdown" >
            <img class="navbar-right-img img-rounded" id="profile-pic" name="profile-pic" src="admin/img/pixel.jpg" />
            <div id="loginName" class="navbar-right-user user-label text-right ellipsis"><?= Yii::$app->user->identity->nama ?></div>
            <div class="navbar-right-last user-label-info text-right">Last online: <?= $now ?></div>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li>
              <a href="index.php?r=site%2Fubah-password&id=<?= Yii::$app->user->identity->NIP ?>">
                <div class="clearfix">
                  <div class="menu-icon"><i class="fa fa-key"></i></div>
                  <div class="menu-text">Ubah Password</div>
                </div>
              </a>
            </li>
            <li>
              <a href="">
                <div class="clearfix">
                  <div class="menu-icon"><i class="fa fa-sign-out"></i></div>
                  <div class="menu-text">Logout</div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div id="alert"></div>

    <div class="navigation">
      <div id="mainmenu" class="row">
        <ul id="mainmenu-group" class="nav navbar-nav">
          <li id="mainmenu-0" class="rootmenu active ">
            <a class="homeicon" href="#" onClick="window.location.href=toHomeURL;">
              <i class="fa fa-institution"></i>
            </a>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Profile<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-kehadiran" class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Profile</div></div>', ['pegawai/profile']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Kehadiran<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-kehadiran" class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Kehadiran</div></div>', ['pegawai/kehadiran']) ?>
              </li>
              <!--
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Kehadiran Berjalan</div></div>', ['pegawai/kehadiran-berjalan']) ?>
              </li>
              -->
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              TPP<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-kehadiran" class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">TPP</div></div>', ['pegawai/tpp']) ?>
              </li>
              <!--
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">TPP Berjalan</div></div>', ['pegawai/tpp-berjalan']) ?>
              </li>
              -->
            </ul>
          </li>
          <li class="rootmenu">
            <?= Html::a('Ubah Password', ['site/ubah-password', 'id' => Yii::$app->user->identity->NIP]) ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="content">
    <div id="body" class="container-fluid">
      <div id="page">
      <?php endif ?>
      <?= $content ?>
      <?php if (!Yii::$app->user->isGuest) : ?>
      </div>
    </div>
  </div>

  <footer id="foot" class="holder">
    <div id="footer-content" class="container-fluid clearfix">
      <div class="footer-banner pull-left clearfix">
      <div class="pull-left content">
        <p>
          &copy <?= date('Y') ?>. Sistem Informasi Kehadiran dan Kinerja Pegawai Online
          <br />
          Badan Kepegawaian Daerah Provinsi Papua Barat
        </p>
        </div>
      </div>
    </div>
    <p id="back-top" style="display: block;">
      <a><img src="back-top.png"/></a>
    </p>
  </footer>

  <?php endif ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
