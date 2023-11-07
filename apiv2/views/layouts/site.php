<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\DashboardAsset;

$asset = DashboardAsset::register($this);
$baseUrl = $asset->baseUrl;

$month = date("n");
if ($month >= 2 && $month <= 7) {
  $year = date("Y")-1;
  $semester = 2;
}
elseif ($month >= 8 && $month <= 12) {
  $year = date("Y");
  $semester = 1;
}
else {
  $year = date("Y")-1;
  $semester = 1;
}
  
$akd = $year.$semester;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?= $this->render('header-topnav.php', ['baseUrl'=>$baseUrl]) ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?= $this->render('content-topnav.php', ['content'=>$content]) ?>
    </div>
    
</div>

<?= $this->render('footer.php', ['baseUrl'=>$baseUrl]) ?>

<?php $this->endBody() ?>
<script>
  $(function() {
    $(".select2").select2();
  });
</script>
</body>
</html>
<?php $this->endPage() ?>
