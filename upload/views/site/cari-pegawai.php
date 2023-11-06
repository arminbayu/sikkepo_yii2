<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$_ym = \DateTime::createFromFormat('Y-m', $bulan);
$current_month = $_ym->format('m');
$current_year = $_ym->format('Y');

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Nama Pegawai</h3></div>
		<div class="col-md-12">
			<label>Nama</label>
			<?= $this->render('_search-pegawai', [
                'current_month' => $current_month,
                'current_year' => $current_year,
                'unit' => $unit,
            ]) ?>
			<br />
		</div>
	</div>
</section>