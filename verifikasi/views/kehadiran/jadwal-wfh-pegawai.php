<?php

use yii\helpers\Html;

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

$begin = new DateTime($dari);
$end = new DateTime($sampai);
$end = $end->modify('+1 day');
$interval = new DateInterval('P1D');
$dateRange = new DatePeriod($begin, $interval ,$end);
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Jadwal WFH Pegawai</h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12">
            <?= Html::beginForm(['simpan-jadwal'], 'post') ?>
            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>
                        <table border="0">
                        <tr>
                        <?php foreach($dateRange as $date) : ?>
                            <td align="center" width="50"><?= $date->format("d/m") ?></td>
                        <?php endforeach; ?>
                        </tr>
                        </table>
                    </th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nama ?></td>
                    <td>
                        <table border="0">
                        <tr>
                        <?php foreach($dateRange as $date) : ?>
                            <td align="center" width="50">
                                <?= Html::hiddenInput('dari', $dari) ?>
                                <?= Html::hiddenInput('sampai', $sampai) ?>
                                <?= Html::hiddenInput('nip', $data->NIP) ?>
                                <?= Html::checkbox('wfh['.$data->NIP.$date->format("Y-m-d").']', false, ['value' => $data->NIP.';'.$date->format("Y-m-d")]) ?>
                            </td>
                        <?php endforeach; ?>
                        </tr>
                        </table>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <div class="form-group" style= "margin-top:20px">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</section>