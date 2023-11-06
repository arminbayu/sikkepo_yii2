<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<div class="form-group">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($file, 'file')->fileInput(['class' => 'btn-primary input-lg', 'style' => 'padding-left:10px'])->label('') ?>

    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>
</div>