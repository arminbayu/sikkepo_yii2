<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php
$script = <<< JS

$('.progress-load').click(function() {
    $('#progress-loader').show();
});

JS;
$this->registerJs($script);
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Proses berhasil! Waktu proses: <?= round(Yii::getLogger()->getElapsedTime(), 2) ?> detik.</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-close"></i> Proses tarik data gagal!</h4>
        <?= Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Daftar Terminal</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>Kode</th>
					<th>IP Address</th>
					<th>Nama</th>
					<th>Unit Kerja</th>
					<th>Action</th>
				</tr>
				<?php foreach($model as $terminal): ?>
				<tr>
					<td><?= $terminal->kode ?></td>
					<td><?= $terminal->ip_address ?></td>
					<td><?= $terminal->nama ?></td>
					<td><?= $terminal->unit->nama ?></td>
					<td>
					<?= Html::a('<i class="fa fa-download"></i> Tarik Data', ['ambil-data', 'kode'=>$terminal->kode], ['class' => 'btn-sm btn-info progress-load']) ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>