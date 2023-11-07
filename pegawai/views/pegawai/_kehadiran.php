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
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Kehadiran Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?> <!--<?= Html::a('<i class="fa fa-print"></i> PDF', ['print-kehadiran-pegawai-pdf', 'nip'=>$nip, 'ym'=>$bulan], ['class' => 'btn-sm btn-primary pull-right', 'target' => '_blank', 'title'=>'Laporan dalam format PDF']) ?>--></h3></div>
        <div class="col-md-12">
            <?= $this->render('_form-kehadiran', [
                'current_month' => $current_month,
                'current_year' => $current_year,
            ]) ?>
            <table class="table table-striped">
                <tr>
                    <th width="100">TANGGAL</th>
                    <th>HARI</th>
                    <th style="text-align: center;">MASUK</th>
                    <th style="text-align: center;">ABSEN SIANG</th>
                    <th style="text-align: center;">KELUAR</th>
                    <th style="text-align: center;">STATUS MASUK</th>
                    <th style="text-align: center;">STATUS ABSEN SIANG</th>
                    <th style="text-align: center;">STATUS KELUAR</th>
                    <th style="text-align: center;">STATUS</th>
                    <th>KETERANGAN</th>
                </tr>
                <?php
                $total_selisih_masuk = 0;
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
                </tr>
                <?php
                // $total_selisih_masuk+=strtotime($data->selisih_jam_masuk);
                // $total_selisih_keluar+=strtotime($data->selisih_jam_keluar);

                $_sm = new \DateTime($data->selisih_jam_masuk, new \DateTimeZone(TIMEZONE));
                $sm = $_sm->format('U');
                $_sk = new \DateTime($data->selisih_jam_keluar, new \DateTimeZone(TIMEZONE));
                $sk = $_sk->format('U');

                $total_selisih_masuk+=$sm;
                $total_selisih_keluar+=$sk;

                $total_kehadiran+=1;
                if ($data->jam_masuk != '' && $data->jam_siang != '' && $data->jam_keluar != '') $total_hadir+=1;
                ?>
                <?php endforeach; ?>
                <?php
                $selisih_masuk_total = DateTime::createFromFormat('U', $total_selisih_masuk);
                $selisih_keluar_total = DateTime::createFromFormat('U', $total_selisih_keluar);
                ?>
                <!--
                <tr style="background-color: #ccc">
                    <th colspan="5">Total</th>
                    <th style="text-align: center;"><?= $selisih_masuk_total->format('H:i:s') ?></th>
                    <th></th>
                    <th style="text-align: center;"><?= $selisih_keluar_total->format('H:i:s') ?></th>
                    <th style="text-align: center;"><?= $total_hadir . '/' . $total_kehadiran ?></th>
                    <th></th>
                </tr>
                -->
            </table>
        </div>
    </div>
</section>