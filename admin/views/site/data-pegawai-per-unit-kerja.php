<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->formatter->locale = 'id-ID';

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai</h3></div>
		<div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th style="text-align: center; width: 30px;">No.</th>
					<th style="text-align: left; width: 150px;">NIP</th>
					<th style="text-align: left;">Nama</th>
					<th style="text-align: center; width: 200px;">Action</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td style="text-align: right;"><?= $i ?></td>
					<td style="text-align: left;"><?= $data->NIP ?></td>
					<td style="text-align: left;"><?= $data->nama ?></td>
					<td style="text-align: center;">
					<?= Html::a('<i class="fa fa-file-text"></i> Data Absen Mobile', ['data-absen-mobile', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
					</td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>