<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
        <div class="col-md-12" style="margin-bottom: 20px"><h3>TPP Pegawai</h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4>Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?></h4></div>
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(['fieldConfig' => [
                'options' => [
                    'tag' => false,
                ],
            ],]); ?>
            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle;">NO.</th>
                    <th style="vertical-align: middle;">NIP</th>
                    <th style="vertical-align: middle;">NAMA</th>
                    <th style="vertical-align: middle; text-align: center;">HD</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KTW</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KHD</th>
                    <th style="vertical-align: middle; text-align: center;">KINERJA<br />(JAM)</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KINERJA</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT TOTAL</th>
                    <th style="vertical-align: middle; text-align: center;">TPP</th>
                    <th style="vertical-align: middle; text-align: center;">TPP DIBAYAR</th>
                    <th style="text-align: center;"></th>
                </tr>
                <?php $i = 1; $jam_kinerja = 0; $bobot_kinerja = 0;?>
                <?php foreach($model as $index => $data): ?>
                <?php $jam_kinerja = ($data->bobot_kinerja >= JAM_KINERJA) ? JAM_KINERJA : $data->bobot_kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2); ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nip->nama ?></td>
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_kehadiran ?>%</td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]bobot_kinerja")->textInput(['style' => 'width:60px; height:10px; margin-bottom:-10px; text-align:center'])->label(false) ?></td>
                    <td style="text-align: center;"><?= $bobot_kinerja ?>%</td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja ?>%</td>
                    <td style="text-align: right;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal($data->nip->kodeTpp->tpp) : 'Not set' ?></td>
                    <td style="text-align: right;"><?= ($data->nip->kodeTpp) ? Yii::$app->formatter->asDecimal((($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp) : '' ?></td>
                    <!--
                    <td><?= ($data->nip->kodeTpp) ? ($data->nip->kodeTpp->beban_kerja + $data->nip->kodeTpp->prestasi_kerja) * ($data->jumlah_total + 40/100) : '' ?></td>
                    -->
                    <td style="text-align: right;">
                    <?= Html::a('Detail', ['tpp-pegawai', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>