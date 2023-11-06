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

$_ym = \DateTime::createFromFormat('Y-m', $bulan);
$current_month = $_ym->format('m');
$current_year = $_ym->format('Y');
?>

<section>
    <div class="row">
        <div class="col-md-12 page-break">
            <div class="col-md-12" style="margin-bottom: 0px; text-align: center;"><h3>LAPORAN TPP BULAN <?= strtoupper($monthList[$current_month]) ?> TAHUN <?= $current_year ?></h3></div>
            <div class="col-md-12" style="margin-bottom: 40px; text-align: center;"><h4><?= $unit->nama ?></h4></div>
            <br />
            <table border="1">
                <tr>
                    <th style="text-align: center; vertical-align: middle; width: 35px;">NO</th>
                    <th style="text-align: center; vertical-align: middle;">NAMA/NIP</th>
                    <th style="text-align: center; vertical-align: middle; width: 50px;">GOL</th>
                    <!--
                    <th style="vertical-align: middle; text-align: center;">HADIR</th>
                    `-->
                    <th style="vertical-align: middle; text-align: center; width: 60px;">KTW</th>
                    <th style="vertical-align: middle; text-align: center; width: 60px;">KHD</th>
                    <th style="vertical-align: middle; text-align: center; width: 60px;">KIN</th>
                    <th style="vertical-align: middle; text-align: center; width: 60px;">TOTAL</th>
                    <th style="vertical-align: middle; text-align: center; width: 104px;" colspan="2">JUMLAH<br />TPP</th>
                    <th style="vertical-align: middle; text-align: center; width: 98px;" colspan="2">PAJAK</th>
                    <th style="vertical-align: middle; text-align: center; width: 104px" colspan="2">TPP<br />DIBAYAR</th>
                    <th style="vertical-align: middle; text-align: center; width: 80px">PARAF</th>
                </tr>
                <?php
                    $i = 1;
                    $jam_kinerja = 0;
                    $bobot_kinerja = 0;
                    $sum_tpp = 0;
                    $sum_pajak = 0;
                    $sum_tpp_dibayar = 0;
                    ?>
                <?php foreach($model as $data): ?>
                <?php $jam_kinerja = ($data->kinerja >= JAM_KINERJA) ? JAM_KINERJA : $data->kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2); ?>
                <?php
                    $gol = explode('/', $data->nip->gol_ruang);
                    $tpp_sebelum_pajak = ($data->nip->kodeTpp) ? round((($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp) : '';
                    $pajak = ($data->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 * (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : (($gol[0] == 'IV') ? 15/100 * (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : 0) : '';

                    $round_pajak = round($pajak);
                    $tpp_dibayar = $tpp_sebelum_pajak - $round_pajak;
                ?>
                <tr>
                    <td style="text-align: right; padding-right: 8px"><?= $i ?>.</td>
                    <td style="padding-left: 8px"><?= $data->nip->nama ?><br /><?= $data->NIP ?></td>
                    <td style="text-align: center;"><?= $data->nip->gol_ruang ?></td>
                    <!--
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    `-->
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?>%</td>
                    <td style="text-align: center;"><?= abs($data->bobot_kehadiran) ?>%</td>
                    <td style="text-align: center;"><?= $bobot_kinerja ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja ?>%</td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($tpp_sebelum_pajak) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($round_pajak) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($tpp_dibayar) ?></td>
                    <td></td>
                </tr>
                <?php
                    $i++;
                    $sum_tpp += $tpp_sebelum_pajak;
                    $sum_pajak += $round_pajak;
                    $sum_tpp_dibayar += $tpp_dibayar;
                ?>
                <?php endforeach; ?>
                <tr>
                    <th colspan="7" style="text-align: middle; padding-left: 8px">TOTAL</th>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_tpp) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_pajak) ?></td>
                    <td style="padding-left: 8px; border-right: 0;">Rp</td><td style="text-align: right; padding-right: 8px; border-left: 0;"><?= Yii::$app->formatter->asDecimal($sum_tpp_dibayar) ?></td>
                </tr>
            </table>
        </div>
        <br /><br />
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
                    <td style="width: 450px; vertical-align: top;">BENDAHARA,</td>
                    <td style="vertical-align: top;">KEPALA <?= $unit->nama ?></td>
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