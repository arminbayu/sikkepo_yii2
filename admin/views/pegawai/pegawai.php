<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th style="text-align: center;">No.</th>
					<th style="text-align: center;">NIP</th>
					<th style="text-align: center;">Nama</th>
					<th style="text-align: center;">Gol. Ruang</th>
					<th style="text-align: center;">Eselon</th>
					<th style="text-align: center;">Gol. TPP</th>
					<th style="text-align: center;">Terminal</th>
					<th style="text-align: center;">No. Absen</th>
					<th style="text-align: center;">Status</th>
					<th style="text-align: center;">Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td style="text-align: right;"><?= $i ?></td>
					<td style="text-align: right;"><?= $data->NIP ?></td>
					<td style="text-align: left;"><?= $data->nama ?></td>
					<td style="text-align: center;"><?= $data->gol_ruang ?></td>
					<td style="text-align: center;"><?= $data->eselon ?></td>
					<td style="text-align: center;"><?= ($data->kode_tpp) ? $data->kodeTpp->eselon . ' ' . $data->kodeTpp->golongan : '' ?></td>
					<td style="text-align: center;"><?= ($data->kode_terminal == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-kode-terminal', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) : $data->kode_terminal ?></td>
					<td style="text-align: center;"><?= ($data->no_absen == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-no-absen', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) : $data->no_absen ?></td>
					<td style="text-align: left;"><?= ($data->status == 1) ? '<i class="fa fa-check-circle text-success"></i> <b class="text-success">Aktif</b>' : '<i class="fa fa-minus-circle text-danger"></i> <b class="text-danger">Tidak Aktif</b>' ?></td>
					<td style="text-align: center;">
					<!--
					<?= Html::a('<i class="fa fa-pencil"></i> Set TPP', ['edit-tpp', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
					-->
					<?= Html::a('<i class="fa fa-file-text"></i> Detail', ['detail-pegawai', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
					<?= Html::a(($data->status == 1) ? '<i class="fa fa-pencil"></i> Set Status' : '<i class="fa fa-pencil"></i> Set Status', ['update-status', 'nip'=>$data->NIP, 'nama'=>$nama, 'unit'=>$data->unit_kerja], ['class' => ($data->status == 1) ? 'btn-sm btn-success' : 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah status ingin diubah?',
								'method' => 'post',
							],]) ?>
					<!--
					<?= Html::a('<i class="fa fa-trash"></i> Hapus', ['delete-pegawai', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-danger']) ?>
					-->
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>