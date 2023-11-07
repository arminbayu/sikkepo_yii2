<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Kode TPP <?= $model->kode ?></h3></div>
		<div class="col-md-12">
		    <?= $this->render('_form-edit-tpp', [
				'model' => $model,
		    ]) ?>
		</div>
	</div>
</section>