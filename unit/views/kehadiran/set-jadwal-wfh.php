<?php

use yii\helpers\Html;
use yii\bootstrap5\Progress;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php
$script = <<< JS

$(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");

JS;
$this->registerJs($script);

?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Berhasil!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')) : ?> 
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Gagal!</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>   
<?php endif; ?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Set Jadwal WFH</h3></div>
		<div class="col-md-12">
			<?= $this->render('_form-jadwal-wfh', [
				'unit' => $unit,
			]) ?>
			<br />
		</div>
	</div>
</section>