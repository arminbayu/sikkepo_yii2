<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$formatter = new NumberFormatter('en_US', NumberFormatter::PERCENT);

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

$_ym = \DateTime::createFromFormat('Y-m-d', $bulan.'-01');
$current_month = $_ym->format('m');
$current_year = $_ym->format('Y');
?>

<section>
    <div class="row">
        <div class="col-md-12 page-break">
            <div class="col-md-12" style="margin-bottom: 0px; text-align: center;"><h3>LAPORAN TPP BULAN <?= strtoupper($monthList[$current_month]) ?> TAHUN <?= $current_year ?></h3></div>
            <div class="col-md-12" style="margin-bottom: 40px; text-align: center;"><h4><?= $unit->nama ?></h4></div>
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; vertical-align: middle; width: 40px;">NO</th>
                    <th style="text-align: center; vertical-align: middle; width: 160px;">NIP</th>
                    <th style="text-align: center; vertical-align: middle;">NAMA</th>
                    <!--
                    <th style="vertical-align: middle; text-align: center;">HADIR</th>
                    `-->
                    <th style="vertical-align: middle; text-align: center; width: 100px;">KETEPATAN</th>
                    <th style="vertical-align: middle; text-align: center; width: 100px;">KEHADIRAN</th>
                    <th style="vertical-align: middle; text-align: center; width: 80px;">KINERJA</th>
                    <th style="vertical-align: middle; text-align: center; width: 80px;">TOTAL</th>
                    <!--
                    <th style="vertical-align: middle; text-align: center;">TPP</th>
                    -->
                    <th style="vertical-align: middle; text-align: center; width: 130px" colspan="2">TPP DIBAYAR</th>
                    <th style="vertical-align: middle; text-align: center; width: 80px">PARAF</th>
                </tr>
                <?php $i = 1; $jam_kinerja = 0; $bobot_kinerja = 0;?>
                <?php foreach($model as $data): ?>
                <?php $jam_kinerja = ($data->kinerja >= JAM_KINERJA) ? JAM_KINERJA : $data->kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2); ?>
                <tr>
                    <td style="text-align: right; padding-right: 8px"><?= $i ?>.</td>
                    <td style="padding-left: 8px"><?= $data->NIP ?></td>
                    <td style="padding-left: 8px"><?= $data->nip->nama ?></td>
                    <!--
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    `-->
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?>%</td>
                    <td style="text-align: center;"><?= abs($data->bobot_kehadiran) ?>%</td>
                    <td style="text-align: center;"><?= $bobot_kinerja ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja ?>%</td>
                    <!--
                    <td style="text-align: center;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal($data->nip->kodeTpp->tpp) : '' ?></td>
                    -->
                    <td style="padding-left: 8px; border-right: 0">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal(round((($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp)) : '0' ?>,-</td>
                    <td></td>
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
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 450px; vertical-align: top;">BENDAHARA</td>
                    <td style="vertical-align: top;"><?= $unit->jab_pl ?></td>
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                    <td></td>
                </tr>
                <tr>
                    <td><span style="text-decoration: underline;"><?= ($unit->bendahara) ? $unit->benUnit->nama : '...' ?></span></td>
                    <td><span style="text-decoration: underline;"><?= ($unit->ka_unit) ? $unit->kaUnit->nama : '...' ?></span></td>
                </tr>
                <tr>
                    <td>NIP: <?= ($unit->bendahara) ? $unit->benUnit->NIP : '...' ?></td>
                    <td>NIP: <?= ($unit->ka_unit) ? $unit->kaUnit->NIP : '...' ?></td>
                </tr>
            </table>
        </div>
    </div>
</section>