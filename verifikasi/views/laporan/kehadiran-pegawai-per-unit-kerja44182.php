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

$_ym = \DateTime::createFromFormat('Y-m', $bulan);
$current_month = $_ym->format('m');
$current_year = $_ym->format('Y');
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Pegawai <?= Html::a('<i class="fa fa-print"></i> PDF', ['print-kehadiran-pegawai-per-unit-kerja-pdf', 'unit'=>$unit->kode, 'ym'=>$bulan], ['class' => 'btn-sm btn-primary pull-right', 'target' => '_blank', 'title'=>'Laporan dalam format PDF']) ?></h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4>Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?></h4></div>
        <div class="col-md-12">
            <?= $this->render('_form-lihat-kehadiran', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th style="text-align: center;">HD</th>
                    <th style="text-align: center;">TH</th>
                    <th style="text-align: center;">TA3X</th>
                    <th style="text-align: center;">TW</th>
                    <th style="text-align: center;">TL</th>
                    <th style="text-align: center;">PC</th>
                    <th style="text-align: center;">TAM</th>
                    <th style="text-align: center;">TAS</th>
                    <th style="text-align: center;">TAP</th>
                    <th style="text-align: center;">Hari Kerja</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nip->nama ?></td>
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_3x - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->datang_tepat_waktu ?></td>
                    <td style="text-align: center;"><?= $data->datang_terlambat ?></td>
                    <td style="text-align: center;"><?= $data->pulang_cepat ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_masuk - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_siang - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_pulang - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->hari_kerja ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>