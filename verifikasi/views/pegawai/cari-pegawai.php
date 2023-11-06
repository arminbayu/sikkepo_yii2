<?php

use yii\helpers\Html;
use yii\bootstrap5\Progress;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Cari Pegawai</h3></div>
		<div class="col-md-12">
			<label>Nama</label>
			<?= $this->render('_search-pegawai') ?>
			<br />
		</div>
	</div>
</section>