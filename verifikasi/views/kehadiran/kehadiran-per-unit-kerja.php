<?php

use yii\helpers\Html;
use common\models\AbsenPegawai;
use yii\bootstrap5\Progress;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$monthList = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
);

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
        <h4><i class="fa fa-check"></i> Proses kehadiran berhasil! Data telah disimpan!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row" id="print">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Pegawai</h3></div>
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
                        <?= Html::a('<i class="fa fa-file-text"></i> Lihat', ['kehadiran-pegawai-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success']) ?>
                        <?= Html::a('Proses Kehadiran', ['proses-kehadiran-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success progress-load']) ?>
                        <!--
                        <?= Html::a('Proses WFH', ['proses-kehadiran-wfh-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success progress-load']) ?>
                        -->
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>