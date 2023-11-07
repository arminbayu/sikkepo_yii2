<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\UnitKerja;

/* @var $this yii\web\View */
/* @var $model admin\models\AbsenPegawai */
/* @var $form yii\widgets\ActiveForm */

$golongan = array(
    'I/a'=>'I/a',
    'I/b'=>'I/b',
    'I/c'=>'I/c',
    'I/d'=>'I/d',
    'II/a'=>'II/a',
    'II/b'=>'II/b',
    'II/c'=>'II/c',
    'II/d'=>'II/d',
    'III/a'=>'III/a',
    'III/b'=>'III/b',
    'III/c'=>'III/c',
    'III/d'=>'III/d',
    'IV/a'=>'IV/a',
    'IV/b'=>'IV/b',
    'IV/c'=>'IV/c',
    'IV/d'=>'IV/d',
    'IV/e'=>'IV/e',
);

$pangkat = array(
    'JURU MUDA (I/a)'=>'JURU MUDA (I/a)',
    'JURU MUDA TINGKAT I (I/b)'=>'JURU MUDA TINGKAT I (I/b)',
    'JURU (I/c)'=>'JURU (I/c)',
    'JURU TINGKAT I (I/d)'=>'JURU TINGKAT I (I/d)',
    'PENGATUR MUDA (II/a)'=>'PENGATUR MUDA (II/a)',
    'PENGATUR MUDA TINGKAT I (II/b)'=>'PENGATUR MUDA TINGKAT I (II/b)',
    'PENGATUR (II/c)'=>'PENGATUR (II/c)',
    'PENGATUR TINGKAT I (II/d)'=>'PENGATUR TINGKAT I (II/d)',
    'PENATA MUDA (III/a)'=>'PENATA MUDA (III/a)',
    'PENATA MUDA TINGKAT I (III/b)'=>'PENATA MUDA TINGKAT I (III/b)',
    'PENATA (III/c)'=>'PENATA (III/c)',
    'PENATA TINGKAT I (III/d)'=>'PENATA TINGKAT I (III/d)',
    'PEMBINA (IV/a)'=>'PEMBINA (IV/a)',
    'PEMBINA TINGKAT I (IV/b)'=>'PEMBINA TINGKAT I (IV/b)',
    'PEMBINA UTAMA MUDA (IV/c)'=>'PEMBINA UTAMA MUDA (IV/c)',
    'PEMBINA UTAMA MADYA (IV/d)'=>'PEMBINA UTAMA MADYA (IV/d)',
    'PEMBINA UTAMA (IV/e)'=>'PEMBINA UTAMA (IV/e)',
);

$eselon = array(
    '----'=>'----',
    'I. A'=>'I. A',
    'I. B'=>'I. B',
    'II. A'=>'II. A',
    'II. B'=>'II. B',
    'III. A'=>'III. A',
    'III. B'=>'III. B',
    'IV. A'=>'IV. A',
    'IV. B'=>'IV. B',
    '16'=>'16',
    '15'=>'15',
    '14'=>'14',
    '13'=>'13',
    '12'=>'12',
    '11'=>'11',
    '10'=>'10',
    '9'=>'9',
    '8'=>'8',
    '7'=>'7',
    '6'=>'6',
    '5'=>'5',
    '4'=>'4',
    '3'=>'3',
    '2'=>'2',
    '1'=>'1',
);

$pendidikan = array(
    'SD'=>'SD',
    'SLTP'=>'SLTP',
    'SLTA UMUM'=>'SLTA UMUM',
    'SLTA KEJURUAN'=>'SLTA KEJURUAN',
    'DIPLOMA I'=>'DIPLOMA I',
    'DIPLOMA II'=>'DIPLOMA II',
    'DIPLOMA III'=>'DIPLOMA III',
    'DIPLOMA IV'=>'DIPLOMA IV',
    'SARJANA'=>'SARJANA',
    'MAGISTER'=>'MAGISTER',
    'DOKTOR'=>'DOKTOR',
);
?>

<?php
$script = <<< JS

$(".select2").select2();

$('#tanggal_lahir').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#tmt_pangkat').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#tmt_jabatan').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#tmt_cpns').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#tmt_pns').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#tmt_gaji').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

JS;
$this->registerJs($script);
?>

<div class="data-pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput() ?>

    <?= $form->field($model, 'tempat_lahir')->textInput() ?>

    <?= $form->field($model, 'tanggal_lahir', ['inputOptions'=>['class'=>'form-control', 'id'=>'tanggal_lahir']])->textInput() ?>

    <?= $form->field($model, 'jenis_kelamin')->dropDownList(array('L'=>'Laki-laki', 'P'=>'Perempuan'), ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'gol_ruang')->dropDownList($golongan, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'tmt_pangkat', ['inputOptions'=>['class'=>'form-control', 'id'=>'tmt_pangkat']])->textInput() ?>

    <?= $form->field($model, 'jabatan')->textInput() ?>

    <?= $form->field($model, 'tmt_jabatan', ['inputOptions'=>['class'=>'form-control', 'id'=>'tmt_jabatan']])->textInput() ?>

    <?= $form->field($model, 'unit_kerja')->hiddeninput(ArrayHelper::map(UnitKerja::find()->all(), 'kode', 'nama'), ['class'=>'form-control select2']) ?>



    
    <?= $form->field($model, 'eselon')->dropDownList($eselon, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'pangkat_cpns')->dropDownList($pangkat, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'tmt_cpns', ['inputOptions'=>['class'=>'form-control', 'id'=>'tmt_cpns']])->textInput() ?>

    <?= $form->field($model, 'pangkat_pns')->dropDownList($pangkat, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'tmt_pns', ['inputOptions'=>['class'=>'form-control', 'id'=>'tmt_pns']])->textInput() ?>

    <?= $form->field($model, 'gaji_pokok')->textInput() ?>

    <?= $form->field($model, 'tmt_gaji', ['inputOptions'=>['class'=>'form-control', 'id'=>'tmt_gaji']])->textInput() ?>

    <?= $form->field($model, 'tingkat_pendidikan')->dropDownList($pendidikan, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'pendidikan_umum')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-stop"></i> Cancel', ['data-pegawai'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
