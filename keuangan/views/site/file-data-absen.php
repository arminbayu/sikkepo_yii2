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
        <h4><i class="fa fa-check"></i> Proses berhasil!.</h4>
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
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Daftar File Absen</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>No.</th>
					<th>Satuan Kerja</th>
					<th>Terminal</th>
					<th>File</th>
					<th>Bulan</th>
					<th>Tanggal Upload</th>
					<th>Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($file as $file): ?>
				<?php
				$bln = explode('-', $file->file);
				$bulan = (new \DateTime($bln[0].'-'.$bln[1].'-1', new \DateTimeZone(TIMEZONE)))->format('M Y');
				$ym = (new \DateTime($bln[0].'-'.$bln[1].'-1', new \DateTimeZone(TIMEZONE)))->format('Y-m')
				?>
				<tr>
					<td><?= $i ?></td>
					<td><?= $file->unitKerja->unit->nama ?></td>
					<td><?= $file->terminal ?></td>
					<td><?= $file->file ?></td>
					<td><?= $bulan ?></td>
					<td><?= $file->tanggal_upload ?></td>
					<td>
					<?= ($file->status == 0) ? Html::a('<i class="fa fa-download"></i> Proses', ['import-data', 'terminal'=>$file->terminal, 'ym'=>$ym], ['class' => 'btn-sm btn-info progress-load']) : 'Sudah Diproses' ?>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>