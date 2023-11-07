<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<style type="text/css">
    table.detail-view th {
        width: 25%;
    }

    table.detail-view td {
        width: 75%;
    }
</style>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai</h3></div>
		<div class="col-md-12">
			<!--<p style="margin-bottom: 20px">
				<?= Html::a('<i class="fa fa-arrow-left"></i> Kembali', ['data-pegawai-per-unit-kerja', 'unit'=>$model->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
			</p> -->

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'NIP',
					'nama',
					'tempat_lahir',
					[
						'attribute' => 'tanggal_lahir',
						'format' => 'raw',
						'value' => date('d M Y', strtotime($model->tanggal_lahir)),
					],
					[
						'attribute' => 'jenis_kelamin',
						'format' => 'raw',
						'value' => ($model->jenis_kelamin == 'L') ? 'LAKI-LAKI' : 'PEREMPUAN',
					],
					'gol_ruang',
					[
						'attribute' => 'tmt_pangkat',
						'format' => 'raw',
						'value' => date('d M Y', strtotime($model->tmt_pangkat)),
					],
					'jabatan',
					[
						'attribute' => 'unit_kerja',
						'format' => 'raw',
						'value' => $model->unitKerja->nama,
					],
					'eselon',
					'pangkat_cpns',
					[
						'attribute' => 'tmt_cpns',
						'format' => 'raw',
						'value' => date('d M Y', strtotime($model->tmt_cpns)),
					],
					'pangkat_pns',
					[
						'attribute' => 'tmt_pns',
						'format' => 'raw',
						'value' => date('d M Y', strtotime($model->tmt_pns)),
					],
					'gaji_pokok',
					[
						'attribute' => 'tmt_gaji',
						'format' => 'raw',
						'value' => date('d M Y', strtotime($model->tmt_gaji)),
					],
					'tingkat_pendidikan',
					'pendidikan_umum',
					[
						'attribute' => 'status',
						'format' => 'raw',
						'value' => ($model->status == 1) ? 'AKTIF' : 'TIDAK AKTIF',
					],
				],
			]) ?>

		<!--	<p>
				<?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-pegawai', 'nip' => $model->NIP, 'unit'=>$model->unit_kerja], ['class' => 'btn btn-primary']) ?>
				<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-pegawai', 'nip' => $model->NIP, 'unit'=>$model->unit_kerja], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => 'Data Pegawai ingin dihapus?',
						'method' => 'post',
					],
				]) ?>
			</p> -->
		</div>
	</div>
</section>