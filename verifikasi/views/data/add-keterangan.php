<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Keterangan</h3></div>
		<div class="col-md-12">
			<?= $this->render('_form-keterangan', [
		    	'model' => $model,
		    ]) ?>
		</div>
	</div>
</section>