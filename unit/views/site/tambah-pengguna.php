<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\UserPegawai */

?>
<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Pengguna Baru</h3></div>
		<div class="col-md-12">
		<?= $this->render('_form-pengguna', [
			'model' => $model,
		]) ?>
		</div>
	</div>
</section>