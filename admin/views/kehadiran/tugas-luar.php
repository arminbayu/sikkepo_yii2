<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Berhasil!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php elseif (Yii::$app->session->hasFlash('failed')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Gagal!</h4>
        <?= Yii::$app->session->getFlash('failed') ?>
    </div>
<?php endif; ?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Tugas Luar</h3></div>
		<div class="col-md-12">
			<?= $this->render('_form-tugas-luar') ?>
			<br />
		</div>
	</div>
</section>