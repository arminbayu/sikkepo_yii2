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

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>TPP</h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4>Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?></h4></div>
        <div class="col-md-12">
            <?= $this->render('_form-tpp', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle; text-align: center;">HD</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KTW</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KHD</th>
                    <th style="vertical-align: middle; text-align: center;">KINERJA<br />(JAM)</th>
                    <!--<th style="vertical-align: middle; text-align: center;">BOBOT KINERJA</th>-->
                    <th style="vertical-align: middle; text-align: center;">BOBOT TOTAL</th>
                    <th style="vertical-align: middle; text-align: center;">TPP</th>
                    <th style="vertical-align: middle; text-align: center;">TPP BELUM PAJAK</th>
                    <th style="text-align: center;"></th>
                </tr>
                <?php $i = 1; $jam_kinerja = 0; $bobot_kinerja = 0;?>
                <?php foreach($model as $index => $data): ?>
                <?php $jam_kinerja = ($data->kinerja >= JAM_KINERJA) ? JAM_KINERJA : $data->kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2); ?>
                <tr>
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_kehadiran ?>%</td>
                    <td style="text-align: center;"><?= $bobot_kinerja ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja ?>%</td>
                    <td style="text-align: right;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal(round($data->nip->kodeTpp->tpp)) : 'Not set' ?></td>
                    <td style="text-align: right;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal(round((($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp)) : '' ?></td>
                    <td style="text-align: right;">
                    <?= Html::a('Detail', ['detail-tpp', 'ym'=>$bulan], ['class' => 'btn-sm btn-success']) ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>