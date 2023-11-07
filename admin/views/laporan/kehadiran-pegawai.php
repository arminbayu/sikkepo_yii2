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
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Pegawai</h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Action</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nama ?></td>
                    <td>
                        <?= Html::a('<i class="fa fa-download"></i> Lihat Kehadiran', ['lihat-kehadiran', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>
                        <!--
                        <?= Html::a('<i class="fa fa-download"></i> Proses Kehadiran', ['kehadiran', 'nip'=>$data->NIP, 'id'=>$data->no_absen], ['class' => 'btn-sm btn-success']) ?>
                        <?= Html::a('<i class="fa fa-gear"></i> Lihat TPP Berjalan', ['ppk-berjalan', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>
                        -->
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>