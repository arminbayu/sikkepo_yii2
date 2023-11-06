<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataKeterangan;
use yii\bootstrap5\Progress;

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

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Absen Manual</h3></div>
		<div class="col-md-12">
			<?php if ($model) : ?>
				<?php $form = ActiveForm::begin(); ?>
				<table class="table table-hover">
					<tr>
						<th>Tanggal</th>
						<th>Jam</th>
						<th>NIP</th>
						<th>Nama</th>
						<th>Absen</th>
						<th>Action</th>
					</tr>
					<?php foreach($model as $model): ?>
					<tr>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= (new \DateTime($model->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $model->jam ?></td>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $model->NIP ?></td>
						<td style="vertical-align: middle;"><?= $model->nip->nama ?></td>
						<td style="vertical-align: middle;"><?= ($model->absen == 1 ) ? 'Masuk' : (($model->absen == 2) ? 'Siang' : 'Pulang' ) ?></td>
						<td style="vertical-align: middle;">
							<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-absen-manual', 'id'=>$model->id], [
								'class' => 'btn-sm btn-danger',
								'data' => [
									'confirm' => 'Apakah absen manual ingin dihapus?',
									'method' => 'post',
								],
							]) ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
				<?php ActiveForm::end(); ?>
			<?php else : ?>
				Data tidak ditemukan.
			<?php endif; ?>
		</div>
	</div>
</section>