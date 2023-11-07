<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php
$script = <<< JS

$('.progress-load').click(function() {
    $('#progress-loader').show();
});

JS;
$this->registerJs($script);
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check"></i> Proses TPP berhasil! Data telah disimpan!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>TPP Pegawai</h3></div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Action</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->kode ?></td>
                    <td><?= $data->nama ?></td>
                    <td>
                    <?= Html::a('<i class="fa fa-file-text"></i> Lihat', ['tpp-pegawai-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success']) ?>
                    <?= ($this->context->checkDataAbsenPegawai($data->kode, $periode)) ? (($this->context->checkDataTppPegawai($data->kode, $periode)) ? Html::a('<i class="fa fa-gear"></i> Proses Ulang', ['proses-tpp-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-danger progress-load', 'data' => ['confirm' => 'Apakah TPP ingin diproses ulang?']]) : Html::a('<i class="fa fa-gear"></i> Proses TPP', ['proses-tpp-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success progress-load'])) : '' ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>