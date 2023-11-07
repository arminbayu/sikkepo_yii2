<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Nomor Absen (PIN)</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th style="text-align: center;">Kode Terminal</th>
					<th>PIN</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= ($data->getPegawai()->exists()) ? $data->pegawai->NIP : '' ?></td>
					<td><?= ($data->getPegawai()->exists()) ? $data->pegawai->nama : '' ?></td>
					<td style="text-align: center;"><?= $data->kode_terminal ?></td>
					<td><?= $data->pin ?></td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>