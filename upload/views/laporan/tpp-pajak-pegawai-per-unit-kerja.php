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

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>TPP Pegawai <?= Html::a('<i class="fa fa-print"></i> PDF', ['print-tpp-pajak-pegawai-per-unit-kerja-pdf', 'unit'=>$unit->kode, 'ym'=>$bulan], ['class' => 'btn-sm btn-primary pull-right', 'target' => '_blank', 'title'=>'Laporan dalam format PDF']) ?></h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4>Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?></h4></div>
        <div class="col-md-12">
            <?= $this->render('_form-detail-tpp-pegawai', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle;">NO.</th>
                    <th style="vertical-align: middle;">NIP</th>
                    <th style="vertical-align: middle;">NAMA</th>
                    <th style="vertical-align: middle; text-align: center;">GOL</th>
                    <th style="vertical-align: middle; text-align: center;">KETEPATAN WAKTU</th>
                    <th style="vertical-align: middle; text-align: center;">KEHADIRAN</th>
                    <th style="vertical-align: middle; text-align: center;">KINERJA</th>
                    <th style="vertical-align: middle; text-align: center;">TOTAL</th>
                    <th style="vertical-align: middle; text-align: center;">TPP</th>
                    <th style="vertical-align: middle; text-align: center;">TPP SBLM PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;">PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;">TPP DIBAYAR</th>
                </tr>
                <?php
                    $i = 1;
                    $sum_tpp = 0;
                    $sum_pajak = 0;
                    $sum_tpp_dibayar = 0;
                ?>
                <?php foreach($model as $data): ?>
                <?php $jam_kinerja = ($data->kinerja >= JAM_KINERJA) ? JAM_KINERJA : $data->kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2); ?>
                <?php
                    $gol = explode('/', $data->nip->gol_ruang);
                    $tpp_sebelum_pajak = ($data->nip->kodeTpp) ? (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : '';
                    $pajak = ($data->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 * (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : (($gol[0] == 'IV') ? 15/100 * (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : 0) : '';
                    
                    $round_pajak = round($pajak);
                    $tpp_dibayar = $tpp_sebelum_pajak - $round_pajak;
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nip->nama ?></td>
                    <td style="text-align: center;"><?= $data->gol ?></td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?>%</td>
                    <td style="text-align: center;"><?= abs($data->bobot_kehadiran) ?>%</td>
                    <td style="text-align: center;"><?= $bobot_kinerja ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja ?>%</td>
                    <td style="text-align: right;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal($data->nip->kodeTpp->tpp) : 'Not set' ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($tpp_sebelum_pajak) ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($round_pajak) ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($tpp_dibayar) ?></td>
                </tr>
                <?php
                    $i++;
                    $sum_tpp += $tpp_sebelum_pajak;
                    $sum_pajak += $round_pajak;
                    $sum_tpp_dibayar += $tpp_dibayar;
                ?>
                <?php endforeach; ?>
                <tr>
                    <th colspan="9" style="text-align: middle;">TOTAL</th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_tpp) ?></th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_pajak) ?></th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_tpp_dibayar) ?></th>
                </tr>
            </table>
        </div>
    </div>
</section>