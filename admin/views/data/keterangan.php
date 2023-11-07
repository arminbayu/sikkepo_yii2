<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Keterangan</h3></div>
		<div class="col-md-12">
			<p style="margin-bottom: 20px">
				<?= Html::a('<i class="fa fa-plus"></i> Tambah Keterangan', ['add-keterangan'], ['class' => 'btn-sm btn-success']) ?>
			</p>

			<table class="table table-striped">
				<tr>
					<th>Kode</th>
					<th>Keterangan</th>
					<th>Action</th>
				</tr>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $data->id ?></td>
					<td><?= $data->keterangan ?></td>
					<td>
						<?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-keterangan', 'id'=>$data->id], ['class' => 'btn-sm btn-warning']) ?>
						<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-keterangan', 'id'=>$data->id], [
							'class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah keterangan ingin dihapus?',
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