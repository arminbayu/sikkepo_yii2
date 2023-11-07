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
$now = $time->format('D, d-m-Y H:i:s');
$date = $time->format('d');
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
            <div id="loginName" class="navbar-right-user user-label text-right ellipsis"><?= Yii::$app->user->identity->pegawai->nama ?></div>
            <div class="navbar-right-last user-label-info text-right">Last online: <?= $now ?></div>
          </a>
          <ul class="dropdown-menu" role="menu">
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
              Administrator<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-administrator" class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-users"></i></div><div class="menu-text">Kelola Pengguna</div></div>', ['site/pengguna']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-users"></i></div><div class="menu-text">Kelola Unit</div></div>', ['site/unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-download"></i></div><div class="menu-text">Tarik Data Absen</div></div>', ['site/daftar-terminal']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-upload"></i></div><div class="menu-text">Upload Data Absen</div></div>', ['site/upload-data-absen']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-search"></i></div><div class="menu-text">Cari Data Absen</div></div>', ['site/cari-data-absen']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Aparatur Sipil Negara<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-user"></i></div><div class="menu-text">Data Pegawai</div></div>', ['pegawai/data-pegawai']) ?>
              </li>
              <!--<li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-search"></i></div><div class="menu-text">Cari Pegawai</div></div>', ['pegawai/cari-pegawai']) ?>
              </li>-->
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Tugas Luar<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Tugas Luar</div></div>', ['kehadiran/tugas-luar']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Entri Tugas Luar</div></div>', ['kehadiran/edit-tugas-luar']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Daftar Penugasan</div></div>', ['kehadiran/daftar-tugas-luar']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
          	
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              WFH<span class="arrow menu-arrow"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
			<li class="col-xs-12">
			<?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Jadwal WFH disampaikan ke BKD</div></div>') ?>
			</li>

             <!-- <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Jadwal WFH</div></div>', ['kehadiran/jadwal-wfh']) ?>
              </li>-->
              
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Entri Jadwal WFH</div></div>', ['kehadiran/edit-jadwal-wfh']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Entri Kehadiran<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-kehadiran" class="dropdown-menu" role="menu">
              <li class="col-xs-6 first">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Upacara</div></div>', ['kehadiran/upacara']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Upacara</div></div>', ['kehadiran/upacara'], ['onclick' => 'return false']) ?>
              </li>
              <li class="col-xs-6 first">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Upacara</div></div>', ['kehadiran/edit-upacara']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Upacara</div></div>', ['kehadiran/edit-upacara'], ['onclick' => 'return false']) ?>
              </li>
              <li class="col-xs-6">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Ketidakhadiran</div></div>', ['kehadiran/ketidakhadiran']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Ketidakhadiran</div></div>', ['kehadiran/ketidakhadiran'], ['onclick' => 'return false']) ?>
              </li>
              <li class="col-xs-6">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Ketidakhadiran</div></div>', ['kehadiran/edit-ketidakhadiran']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Ketidakhadiran</div></div>', ['kehadiran/edit-ketidakhadiran'], ['onclick' => 'return false']) ?>
              </li>
              <!--
              <li class="col-xs-6">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Absen Manual</div></div>', ['kehadiran/absen-manual']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Absen Manual</div></div>', ['kehadiran/absen-manual'], ['onclick' => 'return false']) ?>
              </li>
              <li class="col-xs-6">
                <?= ($date <= 31) ? Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Absen Manual</div></div>', ['kehadiran/edit-absen-manual']) : Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Entri Absen Manual</div></div>', ['kehadiran/edit-absen-manual'], ['onclick' => 'return false']) ?>
              </li>
              -->
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Transaksi<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">  
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-gears"></i></div><div class="menu-text">Proses Kehadiran</div></div>', ['kehadiran/kehadiran-per-unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-gears"></i></div><div class="menu-text">Proses TPP</div></div>', ['tpp/tpp-per-unit-kerja']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Laporan<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-laporan" class="dropdown-menu" role="menu">  
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">Kehadiran Per Pegawai</div></div>', ['laporan/kehadiran-per-pegawai']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">Kehadiran Pegawai Per SKPD</div></div>', ['laporan/kehadiran-per-unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">TPP Per Pegawai</div></div>', ['laporan/tpp-per-pegawai']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">TPP Dengan Pajak Per SKPD</div></div>', ['laporan/tpp-pajak-per-unit-kerja']) ?>
              </li>
            </ul>
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
