<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Absen</h3></div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tr>
					<th>ID</th>
					<th>NIP</th>
					<th>Nama</th>
					<th style="text-align: center;">Kode Terminal</th>
					<th>PIN</th>
					<th>Date Time</th>
					<th style="text-align: center;">Verified</th>
					<th style="text-align: center;">Status</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= ($data->getPegawai()->exists()) ? $data->pegawai->NIP : '' ?></td>
					<td><?= ($data->getPegawai()->exists()) ? $data->pegawai->nama : '' ?></td>
					<td style="text-align: center;"><?= $data->kode_terminal ?></td>
					<td><?= $data->pin ?></td>
					<td><?= $data->date_time ?></td>
					<td style="text-align: center;"><?= $data->ver ?></td>
					<td style="text-align: center;"><?= $data->status ?></td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>

			<?php
			echo LinkPager::widget([
				'pagination' => $pages,
			]);
			?>

			<?php
			// echo GridView::widget([
			//   'dataProvider' => $dataProvider,
			//   'summary' => '',
			//   'showFooter' => false,
			//   'showHeader' => true,
			//   'columns' => [
			//       ['class' => 'yii\grid\SerialColumn'],

			//       [
			//           'attribute'=>'NIP',
			//           'value'=>'pegawai.NIP',
			//           //'contentOptions'=>['style'=>'width: 120px;']
			//       ],
			//       'pin',
			//       'date_time',
			//   ],
			// ]);
			?>
		</div>
	</div>
</section>