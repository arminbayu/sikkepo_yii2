<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

Yii::$app->formatter->locale = 'id-ID';

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>TPP</h3></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px">
			<?= Html::a('<i class="fa fa-plus"></i> Tambah TPP', ['add-tpp'], ['class' => 'btn-sm btn-success']) ?>
			</p>

			<table class="table table-striped">
				<tr>
					<th style="text-align: center;">Kode</th>
					<th style="text-align: center;">TPP</th>
					<th>Keterangan</th>
					<th style="text-align: center;">Action</th>
				</tr>
				<?php foreach($model as $data): ?>
				<tr>
					<td style="text-align: center;"><?= $data->kode ?></td>
					<td style="text-align: center;">Rp <?= Yii::$app->formatter->asDecimal($data->tpp) ?>,-</td>
					<td><?= $data->keterangan ?></td>
					<td style="text-align: center;">
						<?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-tpp', 'kode'=>$data->kode], ['class' => 'btn-sm btn-warning']) ?>
						<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-tpp', 'kode'=>$data->kode], [
							'class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah TPP ingin dihapus?',
								'method' => 'post',
							],
						]) ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>