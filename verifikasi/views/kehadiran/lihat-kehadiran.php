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

$_ym = \DateTime::createFromFormat('Y-m-d', $bulan.'-01');
$current_month = $_ym->format('m');
$current_year = $_ym->format('Y');
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?></h3></div>
        <div class="col-md-12" style="margin-bottom: 20px">
            <table>
                <tr>
                    <td width="80">NIP</td>
                    <td width="10">:</td>
                    <td><?= $data->NIP; ?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?= $data->nama; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <!--
            <?= $this->render('_form-lihat-kehadiran', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
            -->
            <table class="table table-striped">
                <tr>
                    <th width="100">Tanggal</th>
                    <th>Hari</th>
                    <th style="text-align: center;">Masuk</th>
                    <th style="text-align: center;">Absen Siang</th>
                    <th style="text-align: center;">Keluar</th>
                    <th style="text-align: center;">Status Masuk</th>
                    <th style="text-align: center;">Status Absen Siang</th>
                    <th style="text-align: center;">Status Keluar</th>
                    <th style="text-align: center;">Status</th>
                    <th>Keterangan</th>
                </tr>
                <?php
                $total_selisih_masuk = '00:00:00';
                $total_selisih_masuk = (new \DateTime($total_selisih_masuk, new \DateTimeZone(TIMEZONE)))->getTimestamp();
                $total_selisih_keluar = 0;
                $total_kehadiran = 0;
                $total_hadir = 0;
                ?>
                <?php foreach($model as $data): ?>
                <?php $hari = strtotime($data->tanggal); ?>
                <tr>
                    <td><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
                    <td><?= $dayList[(new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('D')]; ?></td>
                    <td style="text-align: center;"><?= $data->jam_masuk ?></td>
                    <td style="text-align: center;"><?= $data->jam_siang ?></td>
                    <td style="text-align: center;"><?= $data->jam_keluar ?></td>
                    <td style="text-align: center;"><?= ($data->selisih_jam_masuk != '') ? $data->status_masuk . ' (' . $data->selisih_jam_masuk . ')' : $data->status_masuk ?></td>
                    <td style="text-align: center;"><?= $data->status_siang ?></td>
                    <td style="text-align: center;"><?= ($data->selisih_jam_keluar != '') ? $data->status_keluar . ' (' . $data->selisih_jam_keluar . ')' : $data->status_keluar ?></td>
                    <td style="text-align: center;"><?= $data->status ?></td>
                    <td><?= $data->keterangan ?></td>
                    <!--
                    <td><?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit-kehadiran', 'id'=>$data->id], ['class' => 'btn-sm btn-warning']) ?></td>
                    -->
                </tr>
                <?php
                // $total_selisih_masuk+=$data->selisih_jam_masuk;
                // $total_selisih_masuk+=strtotime($data->selisih_jam_masuk);
                
                // $total_selisih_masuk = ($data->selisih_jam_masuk != null) ? $total_selisih_masuk->add(new \DateInterval('P'.$data->selisih_jam_masuk.'M')) : $total_selisih_masuk;
                $total_selisih_keluar+=strtotime($data->selisih_jam_keluar);
                $total_kehadiran+=1;
                if ($data->jam_masuk != '' && $data->jam_siang != '' && $data->jam_keluar != '') $total_hadir+=1;
                ?>
                <?php endforeach; ?>

            </table>
        </div>
    </div>
</section>