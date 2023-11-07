<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->formatter->locale = 'id-ID';

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai</h3></div>
		<div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px" > 
				<?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['data-pegawai'], ['class' => 'btn-sm btn-success']) ?>
			</p>
			<table class="table table-striped">
				<tr>
					<th style="text-align: center;">No.</th>
					<th style="text-align: center;">NIP</th>
					<th style="text-align: center;">Nama</th>
					<th style="text-align: center;">Gol. Ruang</th>
					<th style="text-align: center;">Eselon</th>
					<th style="text-align: center; width: 110px;">TPP</th>
					<th style="text-align: center;">Terminal</th>
					<th style="text-align: center;">No. Absen</th>
					<th style="text-align: center; width: 170px;">Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td style="text-align: right;"><?= $i ?></td>
					<td style="text-align: right;"><?= $data->NIP ?></td>
					<td style="text-align: left;"><?= $data->nama ?></td>
					<td style="text-align: center;"><?= $data->gol_ruang ?></td>
					<td style="text-align: center;"><?= $data->eselon ?></td>
					<td style="text-align: right"><?= ($data->kode_tpp != '00' && $data->kode_tpp != '') ? 'Rp '.Yii::$app->formatter->asDecimal($data->kodeTpp->tpp).',-' : '' ?></td>
					<td style="text-align: center;"><?= ($data->kode_terminal == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-kode-terminal', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) : $data->kode_terminal ?></td>
					<td style="text-align: center;"><?= ($data->no_absen == '') ? Html::a('<i class="fa fa-pencil"></i> Set', ['edit-no-absen', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) : $data->no_absen ?></td>
					<td style="text-align: center;">
					<!--<?= Html::a('<i class="fa fa-pencil"></i> Set TPP', ['edit-tpp', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>-->
					<?= Html::a('<i class="fa fa-file-text"></i> Detail', ['detail-pegawai', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
			<?php
			echo LinkPager::widget([
				'pagination' => $pages,
			]);
			?>
		</div>
	</div>
</section>