<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dayList = array(
    'Mon' => 'Senin',
    'Tue' => 'Selasa',
    'Wed' => 'Rabu',
    'Thu' => 'Kamis',
    'Fri' => 'Jumat',
    'Sat' => 'Sabtu',
    'Sun' => 'Minggu'
);

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
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Data Absen Mobile</h3></div>
		<div class="col-md-12">
			<?= $this->render('_form-lihat-absen', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
			<table class="table table-striped">
				<tr>
					<th style="width: 60px">Hari</th>
					<th style="width: 100px">Tanggal</th>
					<th style="width: 80px">Absen</th>
					<th style="width: 100px">Jam</th>
					<th>Absen Dari</th>
					<th>Lokasi</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach($model as $data): ?>
				<tr>
					<td><?= $dayList[(new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('D')]; ?></td>
					<td><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
					<td><?= ($data->absen == 1) ? 'Masuk' : (($data->absen == 2) ? 'Siang' : 'Pulang') ?></td>
					<td><?= $data->jam ?></td>
					<td><?= ($data->origin == 'A') ? 'Aplikasi Mobile' : (($data->origin == 'M')? 'Manual' : '') ?></td>
					<td><?= $data->location ?></td>
				</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</section>