<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Pegawai</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th style="text-align: left; width: 150px">NIP</th>
					<th style="text-align: left;">Nama</th>
					<th style="text-align: center;">Terminal</th>
					<th style="text-align: center;">No. Absen</th>
					<th style="text-align: left;">Status</th>
					<th style="text-align: center;">Action</th>
				</tr>
				<tr>
					<td style="text-align: left;"><?= $data->NIP ?></td>
					<td style="text-align: left;"><?= $data->nama ?></td>
					<td style="text-align: center;"><?= ($data->kode_terminal == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-kode-terminal', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) : $data->kode_terminal ?></td>
					<td style="text-align: center;"><?= ($data->no_absen == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-no-absen', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) : $data->no_absen ?></td>
					<td style="text-align: left;"><?= ($data->status == 1) ? '<i class="fa fa-check-circle text-success"></i> <b class="text-success">Aktif</b>' : '<i class="fa fa-minus-circle text-danger"></i> <b class="text-danger">Tidak Aktif</b>' ?></td>
					<td style="text-align: center;">
					<?= Html::a('<i class="fa fa-file-text"></i> Data Absen', ['data-absen-pegawai', 'kode'=>$data->kode_terminal, 'pin'=>$data->no_absen, 'month'=>Yii::$app->request->get('month'), 'year'=>Yii::$app->request->get('year')], ['class' => 'btn-sm btn-success']) ?>
					</td>
				</tr>

			</table>
		</div>
	</div>
</section>