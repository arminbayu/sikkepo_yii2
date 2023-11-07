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
                    <th style="vertical-align: middle;">NAMA</th>
                    <th style="vertical-align: middle; text-align: center;">HD</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KHD (%)</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KTW (%)</th>
                    <th style="vertical-align: middle; text-align: center;">KINERJA<br />(JAM)</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT KINERJA (%)</th>
                    <th style="vertical-align: middle; text-align: center;">BOBOT TOTAL<br />(%)</th>
                    <th style="vertical-align: middle; text-align: center;">TPP</th>
                    <th style="vertical-align: middle; text-align: center;">TPP SBLM PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;">PAJAK</th>
                    <th style="vertical-align: middle; text-align: center;">PAJAK TPP</th>
                    <th style="vertical-align: middle; text-align: center;">TPP FINAL</th>
                    <th style="text-align: center;"></th>
                </tr>
                <?php $i = 1; $jam_kinerja = 0; $bobot_kinerja = 0;?>
                <?php foreach($model as $index => $data): ?>
                <?php
                    $const_jam_kinerja = JAM_KINERJA;
                    $const_bobot_kinerja = BOBOT_KINERJA;
                    $data_bobot_ketepatan = $data->bobot_ketepatan;
                    $data_bobot_kehadiran = $data->bobot_kehadiran;
                    $data_kinerja = $data->kinerja;
                    $data_tpp = ($data->tpp) ? $data->tpp : $data->nip->kodeTpp->tpp;
                    $gol = explode('/', $data->nip->gol_ruang);
                    $pajak = ($data->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 : (($gol[0] == 'IV') ? 15/100 : 0) : '';
                    $tpp_sebelum_pajak = ($data->nip->kodeTpp) ? (($data->bobot_ketepatan + $data->bobot_kehadiran + $bobot_kinerja)/100) * $data->nip->kodeTpp->tpp : '';
                    $pajak_tpp = $tpp_sebelum_pajak * $pajak;
                ?>
                <?php $jam_kinerja = ($data_kinerja >= $const_jam_kinerja) ? $const_jam_kinerja : $data_kinerja; ?>
                <?php $bobot_kinerja = round($jam_kinerja/$const_jam_kinerja*$const_bobot_kinerja, 2); ?>
                <?php
$script[$index] = <<< JS

$('#prestasiperilakukerja-$index-kinerja').on('input', function(){
    var js_jam_kinerja = ($('#prestasiperilakukerja-$index-kinerja').val() >= $const_jam_kinerja) ? $const_jam_kinerja : $('#prestasiperilakukerja-$index-kinerja').val();
    var js_bobot_kinerja = Math.round(((js_jam_kinerja/$const_jam_kinerja*$const_bobot_kinerja) + Number.EPSILON) * 100) / 100;
    var js_bobot_total = $data_bobot_ketepatan+$data_bobot_kehadiran+js_bobot_kinerja;
    var js_tpp_sebelum_pajak = Math.round(($data_bobot_ketepatan+$data_bobot_kehadiran+js_bobot_kinerja)/100 * $data_tpp);
    var js_pajak = Math.round($pajak * js_tpp_sebelum_pajak);
    var js_tpp_final = js_tpp_sebelum_pajak - js_pajak;

    $('#prestasiperilakukerja-$index-bobot_kinerja').val(js_bobot_kinerja);
    //$('#bobot-total-$index').html(js_bobot_total);
    $('#prestasiperilakukerja-$index-jumlah_total').val(js_bobot_total);
    
    $('#tpp-sebelum-pajak-$index').html(new Intl.NumberFormat('id-ID').format(js_tpp_sebelum_pajak));
    $('#prestasiperilakukerja-$index-tpp_sebelum_pajak').val(js_tpp_sebelum_pajak);
    
    $('#prestasiperilakukerja-$index-pajak').val($pajak);
    
    $('#pajak-tpp-$index').html(new Intl.NumberFormat('id-ID').format(js_pajak));
    $('#prestasiperilakukerja-$index-pajak_tpp').val(js_pajak);
    
    $('#tpp-final-$index').html(new Intl.NumberFormat('id-ID').format(js_tpp_final));
    $('#prestasiperilakukerja-$index-tpp_final').val(js_tpp_final);

});

JS;
$this->registerJs($script[$index]);
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td>
                        <?= $data->nip->nama ?>
                        <?php // $form->field($data, "[$index]unit_kerja")->hiddenInput(['style' => 'width:35px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'value' => $unit->kode])->label(false) ?>
                    </td>
                    <td style="text-align: center;"><?= $data->hadir ?></td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]bobot_kehadiran")->textInput(['style' => 'width:50px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', '' => true, 'value' => ($data->hadir == 0 && $data->bobot_ketepatan == 0 ) ? 0 : $data->bobot_kehadiran])->label(false) ?></td>
                    <td style="text-align: center;"><?= $data->bobot_ketepatan ?></td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]kinerja")->textInput(['style' => 'width:50px; height:10px; margin-bottom:-10px; text-align:center'])->label(false) ?></td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]bobot_kinerja")->textInput(['style' => 'width:50px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?></td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]jumlah_total")->textInput(['style' => 'width:60px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?></td>
                    
                    <td style="text-align: right;">
                        <?= ($data_tpp) ? Yii::$app->formatter->asDecimal($data_tpp) : NULL ?>
                        <?php // $form->field($data, "[$index]tpp")->hiddenInput(['style' => 'width:80px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true, 'value' => ($data_tpp) ? $data_tpp : NULL])->label(false) ?>
                    </td>
                    <td style="text-align: center;">
                        <div id="tpp-sebelum-pajak-<?= $index ?>"><?= Yii::$app->formatter->asDecimal($data->tpp_sebelum_pajak) ?></div>
                        <?= $form->field($data, "[$index]tpp_sebelum_pajak")->hiddenInput(['style' => 'width:80px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?>
                    </td>
                    <td style="text-align: center;"><?= $form->field($data, "[$index]pajak")->textInput(['style' => 'width:50px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?></td>
                    
                    <td style="text-align: center;">
                        <div id="pajak-tpp-<?= $index ?>"><?= Yii::$app->formatter->asDecimal($data->pajak_tpp) ?></div>
                        <?= $form->field($data, "[$index]pajak_tpp")->hiddenInput(['style' => 'width:70px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?>
                    </td>
                    <td style="text-align: right;">
                        <div id="tpp-final-<?= $index ?>"><?= Yii::$app->formatter->asDecimal($data->tpp_final) ?></div>
                        <?= $form->field($data, "[$index]tpp_final")->hiddenInput(['style' => 'width:80px; height:10px; margin-bottom:-10px; text-align:center; border:0; background-color:transparent', 'readonly' => true])->label(false) ?>
                    </td>
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