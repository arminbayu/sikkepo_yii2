<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$formatter = new NumberFormatter('en_US', NumberFormatter::PERCENT);
Yii::$app->formatter->locale = 'id-ID';

$monthList = array(
    '01' => 'JANUARI',
    '02' => 'FEBRUARI',
    '03' => 'MARET',
    '04' => 'APRIL',
    '05' => 'MEI',
    '06' => 'JUNI',
    '07' => 'JULI',
    '08' => 'AGUSTUS',
    '09' => 'SEPTEMBER',
    '10' => 'OKTOBER',
    '11' => 'NOVEMBER',
    '12' => 'DESEMBER'
);

$_year = \DateTime::createFromFormat('Y', $tahun);
$current_year = $_year->format('Y');
?>

<section>
    <div class="row">
        <div class="col-md-12 page-break">
            <div class="col-md-12" style="margin-bottom: 0px; text-align: center;"><h3>REKAP LAPORAN TPP TAHUN <?= $current_year ?></h3></div>
            <div class="col-md-12" style="margin-bottom: 40px; text-align: center;"><h4><?= $unit->nama ?></h4></div>
            <table class="table table-bordered" style="font-size: 0.8em">
                <tr>
                    <th style="text-align: center; vertical-align: middle; width: 35px;">NO</th>
                    <th style="text-align: center; vertical-align: middle; width: 200px;">BULAN</th>
                    <th style="vertical-align: middle; text-align: center;" colspan="2">TPP SEBELUM PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;" colspan="2">PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;" colspan="2">TPP DIBAYARKAN</th>
                </tr>
                <?php
                    $i = 1;
                    $sum_tpp = 0;
                    $sum_pajak = 0;
                    $sum_tpp_dibayar = 0;
                    ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td style="text-align: right; padding-right: 8px"><?= $i ?>.</td>
                    <td style="padding-left: 8px"><?= $monthList[$data['bulan']] ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($data['tpp']) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($data['pajak_tpp']) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($data['tpp_final']) ?></td>
                </tr>
                <?php
                    $i++;
                    $sum_tpp += $data['tpp'];
                    $sum_pajak += $data['pajak_tpp'];
                    $sum_tpp_dibayar += $data['tpp_final'];
                ?>
                <?php endforeach; ?>
                <tr>
                    <th colspan="2" style="text-align: middle; padding-left: 8px">JUMLAH</th>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_tpp) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_pajak) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_tpp_dibayar) ?></td>
                </tr>
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