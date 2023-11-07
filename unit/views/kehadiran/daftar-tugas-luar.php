<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataKeterangan;
use yii\bootstrap5\Progress;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$status = array(
    1 => 'Hadir',
    0 => 'Tidak Hadir',
);

?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
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
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Daftar Tugas Luar</h3></div>
		<div class="col-md-12">
			<?php $form = ActiveForm::begin(); ?>
			<table class="table table-hover">
		<tr>
					<th style="width: 80px">Tanggal</th>
					<th style="width: 140px">NIP</th>
					<th>Nama</th>
					<th style="text-align: center; width: 90px">Dari Jam</th>
					<th style="text-align: center; width: 90px">Sampai Jam</th>
					<th style="text-align: center; width: 80px">Status</th>
					<th style="text-align: center; width: 80px">Photo</th>
					<th style="width: 170px">Action</th>
				</tr>
				<?php foreach($model as $data): ?>
				<?php $hari = strtotime($data->tanggal); ?>
				<tr>
					<td style="vertical-align: middle;" style="vertical-align: middle;"><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
					<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $data->NIP ?></td>
					<td style="vertical-align: middle;"><?= $data->nip->nama ?></td>
					<td style="vertical-align: middle;"><?= $data->dari_jam ?></td>
					<td style="vertical-align: middle;"><?= $data->sampai_jam ?></td>
					<td style="vertical-align: middle; text-align: center;"><?= ($data->status == 1) ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-times-circle text-danger"></i>' ?></td>
					<td style="vertical-align: middle; text-align: center;"><?= ($data->photo != null) ? Html::button('<i class="fa fa-check"></i> Lihat', ['value' => Url::to(['kehadiran/lihat-photo-tugas-luar', 'id'=>$data->id]), 'class' => 'btn-xs btn-primary', 'id' => 'modalButton']) : '<i class="fa fa-times-circle text-danger"></i>' ?></td>
					<td style="vertical-align: middle;">
						<?= Html::a('<i class="fa fa-check"></i> Detail', ['detail-tugas-luar', 'id'=>$data->id], ['class' => 'btn-sm btn-success']) ?>
						<?= ($data->status != 1) ? null : Html::a('<i class="fa fa-times"></i> Cancel', ['cancel-tugas-luar', 'id'=>$data->id], ['class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah Tugas Luar ingin di-cancel?',
								'method' => 'post',
							],]) ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</section>

<?php 
    Modal::begin([
        'header' => '<h4>Photo Tugas Luar</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);

    echo '<div id="modalContent" style="height: 350px; padding: 0;"></div>';

    Modal::end();
?>

<?php
$script = <<< JS

$('#modalButton').click(function() {
    $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});

JS;
$this->registerJs($script);
?>