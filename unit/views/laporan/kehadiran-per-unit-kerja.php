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

$('#progress').click(function() {
    $('#progress-bar').show();
});

JS;
$this->registerJs($script);
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Pegawai per Satuan Kerja</h3></div>
        <div class="col-md-12">
            <!--
            <p>
                <?= Html::a('<i class="fa fa-gears"></i> Proses Kehadiran Semua Pegawai Bulan ' . $monthList[date('m', $bulan)], ['proses-kehadiran-all', 'm'=>date('m', $bulan)], ['class' => 'btn-sm btn-success']) ?>
            </p>
            -->
            <span id="progress-bar" style="display: none;">
            <?php
            echo Progress::widget([
            'percent' => 70,
            'barOptions' => ['class' => 'progress-bar-primary', 'id' => 'percent'],
            'options' => ['class' => 'active progress-striped']
            ]);
            ?>
            </span>
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
                        <?= Html::a('<i class="fa fa-download"></i> Lihat', ['kehadiran-pegawai-per-unit-kerja', 'unit'=>$data->kode], ['class' => 'btn-sm btn-success']) ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>