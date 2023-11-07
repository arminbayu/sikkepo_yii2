<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai</h3></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px">
				<!--
				<?= Html::a('<i class="fa fa-gears"></i> Lihat Semua Data Pegawai', ['data-pegawai-all'], ['class' => 'btn-sm btn-success']) ?>
				-->
				<?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['tambah-pegawai'], ['class' => 'btn-sm btn-success']) ?>
			</p>
			<table class="table table-striped">
				<tr>
					<th>No.</th>
					<th>Kode</th>
					<th>Nama</th>
					<th>Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= $data->kode ?></td>
					<td><?= $data->nama ?></td>
					<td>
					<?= Html::a('<i class="fa fa-file-text-o"></i> Lihat', ['data-pegawai-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success']) ?>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>