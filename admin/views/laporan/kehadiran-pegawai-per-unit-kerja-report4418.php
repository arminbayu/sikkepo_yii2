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

<section>
    <div class="row">
        <div class="col-md-12 page-break">
            <div class="col-md-12" style="margin-bottom: 0px; text-align: center;"><h3>LAPORAN KEHADIRAN BULAN <?= strtoupper($monthList[$current_month]) ?> TAHUN <?= $current_year ?></h3></div>
            <div class="col-md-12" style="margin-bottom: 40px; text-align: center;"><h4><?= $unit->nama ?></h4></div>
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; width: 40px;">NO</th>
                    <th style="text-align: center; width: 160px;">NIP</th>
                    <th style="text-align: center; width: 200px;">NAMA</th>
                    <th style="text-align: center; width: 50px;">HD</th>
                    <th style="text-align: center; width: 50px;">TH</th>
                    <th style="text-align: center; width: 50px;">TA3X</th>
                    <th style="text-align: center; width: 50px;">TW</th>
                    <th style="text-align: center; width: 50px;">TL</th>
                    <th style="text-align: center; width: 50px;">PC</th>
                    <th style="text-align: center; width: 50px;">TAM</th>
                    <th style="text-align: center; width: 50px;">TAS</th>
                    <th style="text-align: center; width: 50px;">TAP</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td style="text-align: right; padding-right: 8px"><?= $i ?>.</td>
                    <td style="padding-left: 8px"><?= $data->NIP ?></td>
                    <td style="padding-left: 8px"><?= $data->nip->nama ?></td>
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_3x - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->datang_tepat_waktu ?></td>
                    <td style="text-align: center;"><?= $data->datang_terlambat ?></td>
                    <td style="text-align: center;"><?= $data->pulang_cepat ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_masuk - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_siang - $data->tidak_hadir ?></td>
                    <td style="text-align: center;"><?= $data->tidak_absen_pulang - $data->tidak_hadir ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-md-12" style="margin-top: 10px; page-break-inside: avoid;">
            <?php
                $time = new \DateTime('now', new \DateTimeZone(TIMEZONE));
                $now = $time->format('d-m-Y');
            ?>
            <table class="table">
                <tr>
                    <td>MANOKWARI, <?= $now ?></td>
                </tr>
                <tr>
                    <td>KEPALA <?= $unit->nama ?>,</td>
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                </tr>
                <tr>
                    <td><span style="text-decoration: underline;"><?= ($unit->ka_unit) ? $unit->kaUnit->nama : '...' ?></span></td>
                </tr>
                <tr>
                    <td>NIP: <?= ($unit->ka_unit) ? $unit->kaUnit->NIP : '...' ?></td>
                </tr>
            </table>
        </div>
    </div>
</section>