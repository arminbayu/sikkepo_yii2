<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\CustomAsset;
use common\models\User;

$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
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
  <!-- Start header  
  <header id="mu-header" style="background-color: #2469a5; color: #ffffff">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="mu-header-area">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="mu-header-top-left">
                  <div class="mu-top-email">
                    <i class="fa fa-envelope"></i>
                    <span>info@sikkepo.com</span>
                  </div>
                  <div class="mu-top-phone">
                    <i class="fa fa-phone"></i>
                    <span>(021) 123 4567</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="mu-header-top-right">
                  <nav>
                    <ul class="mu-top-social-nav">
                      <li><a href="#"><span class="fa fa-facebook" style="color: #ffffff"></span></a></li>
                      <li><a href="#"><span class="fa fa-twitter" style="color: #ffffff"></span></a></li>
                      <div class="pull-right">
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <?= Html::submitButton('Logout', ['class' => 'btn-danger', 'style'=>'padding:0px 6px; margin:0; border:0']) ?>
                        <?= Html::endForm() ?>
                      </div>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  End header  -->
  <?php endif ?>
  <!-- Start menu -->
  <section id="mu-menu">
    <nav class="navbar navbar-default" role="navigation" style="background-color: #f9f9f9">  
      <div class="container">
        <div class="navbar-header">
          <!-- FOR MOBILE VIEW COLLAPSED BUTTON -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- LOGO -->              
          <!-- TEXT BASED LOGO -->
          <?= Html::a('<div style="float:left; margin-right:10px"><img src="custom/img/lambang.png" height="50" alt="logo"></div><div style="font-size:35px; font-weight:bold; margin-top:3px; color:#5d96a0">SIKKEPO</div>', ['site/index'], ['class' => '', 'style' => 'width:250px; position:absolute; top:12px']) ?>
          <!-- IMG BASED LOGO  -->
          <!--<?= Html::a('<img src="custom/img/logo.png" alt="logo">', ['site/index'], ['class' => 'navbar-brand']) ?>-->
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul id="top-menu" class="nav navbar-nav navbar-right main-nav">
            <?php if (Yii::$app->user->isGuest) : ?>
              <li><?= Html::a('BKD PROVINSI PAPUA BARAT', ['site/index']) ?></li>
            <?php endif ?>
            <?php if (!Yii::$app->user->isGuest) : ?>
            <li><?= Html::a('Lihat Kehadiran', ['staff/kehadiran']) ?></li>
            <li><?= Html::a('Lihat Tunjangan Perbaikan Penghasilan (TPP)', ['site/index']) ?></li>
            <li>
              <div class="pull-right" style="margin-left: 20px">
                <?= Html::beginForm(['/site/logout'], 'post') ?>
                <?= Html::submitButton('Logout', ['class' => 'btn-danger', 'style'=>'padding:0px 6px; margin:0; border:0; margin-top:24px']) ?>
                <?= Html::endForm() ?>
              </div>
            </li>
            <?php endif ?>
          </ul>                     
        </div><!--/.nav-collapse -->        
      </div>     
    </nav>
  </section>
  <!-- End menu -->
  <!-- Start search box -->
  <div id="mu-search">
    <div class="mu-search-area">      
      <button class="mu-search-close"><span class="fa fa-close"></span></button>
      <div class="container">
        <div class="row">
          <div class="col-md-12">            
            <form class="mu-search-form">
              <input type="search" placeholder="Type Your Keyword(s) & Hit Enter">              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End search box -->
  <div>
      <!-- Page breadcrumb -->
      <section id="mu-page-breadcrumb">
       <div class="container" >
         <div class="row" >
           <div class="col-md-12">
             <div class="mu-page-breadcrumb-area">
               <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
               ]) ?>
             </div>
           </div>
         </div>
       </div>
      </section>
      <!-- End breadcrumb -->
      <?= $content ?>
  </div>
  <!-- Start footer -->
  <footer id="mu-footer">
    <!-- start footer bottom -->
    <div class="mu-footer-bottom">
      <div class="container">
        <div class="mu-footer-bottom-area">
          <p><strong>&copy; SIKKEPO <?= date('Y') ?>.</strong> All rights reserved.</p>
        </div>
      </div>
    </div>
    <!-- end footer bottom -->
  </footer>
  <!-- End footer -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
