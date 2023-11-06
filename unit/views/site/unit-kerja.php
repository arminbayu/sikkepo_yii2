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
			<table class="table table-striped">
				<tr>
					<td width="150">Kode</td><td width="20">:</td><td><?= $data->kode ?></td>
				</tr>
				<tr>
					<td>Nama</td><td>:</td><td><?= $data->nama ?></td>
				</tr>
				<tr>
					<td>Kepala Unit</td><td>:</td><td><?= ($data->ka_unit) ? $data->kaUnit->nama : '' ?></td>
				</tr>
				<tr>
					<td>Bendahara</td><td>:</td><td><?= ($data->bendahara) ? $data->benUnit->nama : '' ?></td>
				</tr>
				<tr>
					<td>Alamat</td><td>:</td><td><?= $data->alamat ?></td>
				</tr>
				<tr>
					<td>Telp</td><td>:</td><td><?= $data->telp ?></td>
				</tr>
				<tr>
					<td>Koordinat</td><td>:</td><td><?= $data->koordinat ?><!-- <?= Html::a('<i class="fa fa-pencil"></i> Ubah Koordinat', ['edit-koordinat', 'id'=>$data->kode], ['class' => 'btn-sm btn-success']) ?>--></td>
				</tr>
			</table>
			<table>
				<tr>
					<!--<td colspan="3" style="text-align: left;">
						<?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['edit-unit-kerja', 'id'=>$data->kode], ['class' => 'btn btn-warning']) ?> 
					</td>-->
				</tr>
			</table>
		</div>
	</div>
</section>