<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Unit Kerja</h3></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px">
			<?= Html::a('<i class="fa fa-plus"></i> Tambah Unit Kerja', ['add-unit-kerja'], ['class' => 'btn-sm btn-success']) ?>
			</p>

			<table class="table table-striped">
				<tr>
					<th style="width: 20px">No.</th>
					<th style="width: 30px">Kode</th>
					<th>Nama</th>
					<th style="width: 150px">Kepala Unit</th>
					<th style="width: 150px">Bendahara</th>
					<th style="width: 150px">Penandatangan Laporan</th>
					<th>Alamat</th>
					<th style="width: 100px">Telp</th>
					<th style="width: 160px; text-align: center;">Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= $data->kode ?></td>
					<td><?= $data->nama ?></td>
					<td><?= ($data->ka_unit) ? $data->kaUnit->nama : '' ?></td>
					<td><?= ($data->bendahara) ? $data->benUnit->nama : '' ?></td>
					<td><?= $data->jab_pl ?></td>
					<td><?= $data->alamat ?></td>
					<td><?= $data->telp ?></td>
					<td style="text-align: center;">
						<?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-unit-kerja', 'id'=>$data->kode], ['class' => 'btn-sm btn-warning']) ?>
						<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-unit-kerja', 'id'=>$data->kode], [
							'class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah unit kerja ingin dihapus?',
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