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

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Rekap Laporan TPP Tahun <?= $current_year ?> <?= Html::a('<i class="fa fa-print"></i> PDF', ['print-tpp-pajak-tahunan-per-unit-kerja-pdf', 'unit'=>$unit->kode, 'year'=>$tahun], ['class' => 'btn-sm btn-primary pull-right', 'target' => '_blank', 'title'=>'Laporan dalam format PDF']) ?></h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12">
            <?= $this->render('_form-laporan-tpp-tahunan', [
                'current_year' => $current_year,
            ]) ?>
            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle;">NO.</th>
                    <th style="vertical-align: middle;">BULAN</th>
                    <th style="vertical-align: middle; text-align: right;">TPP SEBELUM PAJAK</th>
                    <th style="vertical-align: middle; text-align: right;">PAJAK</th>
                    <th style="vertical-align: middle; text-align: right;">TPP DIBAYARKAN</th>
                </tr>
                <?php
                    $i = 1;
                    $sum_tpp = 0;
                    $sum_pajak = 0;
                    $sum_tpp_dibayar = 0;
                ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td style="width: 50px"><?= $i ?></td>
                    <td><?= $monthList[$data['bulan']] ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($data['tpp']) ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($data['pajak_tpp']) ?></td>
                    <td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($data['tpp_final']) ?></td>
                </tr>
                <?php
                    $i++;
                    $sum_tpp += $data['tpp'];
                    $sum_pajak += $data['pajak_tpp'];
                    $sum_tpp_dibayar += $data['tpp_final'];
                ?>
                <?php endforeach; ?>
                <tr>
                    <th colspan="2" style="text-align: middle;">TOTAL</th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_tpp) ?></th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_pajak) ?></th>
                    <th style="text-align: right;"><?= Yii::$app->formatter->asDecimal($sum_tpp_dibayar) ?></th>
                </tr>
            </table>
        </div>
    </div>
</section>