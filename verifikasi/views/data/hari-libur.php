<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dayList = array(
    'Mon' => 'Senin',
    'Tue' => 'Selasa',
    'Wed' => 'Rabu',
    'Thu' => 'Kamis',
    'Fri' => 'Jumat',
    'Sat' => 'Sabtu',
    'Sun' => 'Minggu'
);

$date = new \DateTime('now', new \DateTimeZone(TIMEZONE));
$tahun = $date->format('Y');

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Hari Libur Tahun <?= $tahun ?></h3></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px">
				<?= Html::a('<i class="fa fa-plus"></i> Tambah Hari Libur', ['add-hari-libur'], ['class' => 'btn-sm btn-success']) ?>
			</p>

			<table class="table table-striped">
				<tr>
					<th>No.</th>
					<th>Hari</th>
					<th>Tanggal</th>
					<th>Keterangan</th>
					<th>Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= $dayList[(new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('D')]; ?></td>
					<td><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
					<td><?= $data->keterangan ?></td>
					<td>
						<?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-hari-libur', 'id'=>$data->id], ['class' => 'btn-sm btn-warning']) ?>
						<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-hari-libur', 'id'=>$data->id], [
							'class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah hari libur ingin dihapus?',
								'method' => 'post',
							],
						]) ?>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>