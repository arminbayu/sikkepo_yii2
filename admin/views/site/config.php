<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Konfigurasi SIKKEPO Mobile</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<td width="200">Radius</td><td width="20">:</td><td><?= $data->radius ?> meter</td>
				</tr>
				<tr>
					<td>Koordinat Lokasi</td><td>:</td><td><?= ($data->location_status == 1) ? 'Aktif' : 'Non Aktif' ?></td>
				</tr>
				<tr>
					<td>Batas awal Absen Masuk</td><td>:</td><td><?= $data->awal_m ?></td>
				</tr>
				<tr>
					<td>Batas akhir Absen Masuk</td><td>:</td><td><?= $data->akhir_m ?></td>
				</tr>
				<tr>
					<td>Batas awal Absen Siang</td><td>:</td><td><?= $data->awal_s ?></td>
				</tr>
				<tr>
					<td>Batas akhir Absen Siang</td><td>:</td><td><?= $data->akhir_s ?></td>
				</tr>
				<tr>
					<td>Batas awal Absen Pulang</td><td>:</td><td><?= $data->awal_p ?></td>
				</tr>
				<tr>
					<td>Batas akhir Absen Pulang</td><td>:</td><td><?= $data->akhir_p ?></td>
				</tr>
			</table>
			<table>
				<tr>
					<td colspan="3" style="text-align: left;">
						<?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['edit-config', 'id'=>$data->id], ['class' => 'btn btn-warning']) ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</section>