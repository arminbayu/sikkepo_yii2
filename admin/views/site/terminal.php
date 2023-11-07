<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Daftar Terminal</h3></div>
		<div class="col-md-12">
			<?= Html::a('<i class="fa fa-plus"></i> Tambah Terminal', ['tambah-terminal'], ['class' => 'btn-sm btn-success']) ?><br /><br />
			<table class="table table-striped">
				<tr>
					<th>Kode</th>
					<th>IP Address</th>
					<th>Nama</th>
					<th>Unit Kerja</th>
					<th>Action</th>
				</tr>
				<?php foreach($model as $terminal): ?>
				<tr>
					<td><?= $terminal->kode ?></td>
					<td><?= $terminal->ip_address ?></td>
					<td><?= $terminal->nama ?></td>
					<td><?= ($terminal->unit) ? $terminal->unit->nama : '' ?></td>
					<td>
					<?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['edit-terminal', 'kode'=>$terminal->kode], ['class' => 'btn-sm btn-warning']) ?>
					<!--
					<?= Html::a('<i class="fa fa-trash"></i> Hapus', ['delete-terminal', 'kode'=>$terminal->kode], [
							'class' => 'btn-sm btn-danger',
							'data' => [
								'confirm' => 'Apakah terminal ingin dihapus?',
								'method' => 'post',
							],
						]) ?>
					-->
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>