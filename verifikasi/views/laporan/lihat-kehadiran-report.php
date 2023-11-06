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

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-bottom: 0px; height: 80px; text-align: center; border: 0px solid #000"><h3>LAPORAN KEHADIRAN BULAN <?= strtoupper($monthList[$current_month]) ?> TAHUN <?= $current_year ?></h3></div>
            <table class="table" style="margin-bottom: 20px; font-weight: bold;">
                <tr>
                    <td width="80">NIP</td>
                    <td width="10">:</td>
                    <td><?= $pegawai->NIP; ?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?= $pegawai->nama; ?></td>
                </tr>
            </table>

            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; width: 100px">Tanggal</th>
                    <th style="text-align: center; width: 70px">Hari</th>
                    <th style="text-align: center; width: 88px">Absen<br />Masuk</th>
                    <th style="text-align: center; width: 88px">Absen<br />Siang</th>
                    <th style="text-align: center; width: 88px">Absen<br />Pulang</th>
                    <th style="text-align: center; width: 112px">Status<br />Absen Masuk</th>
                    <th style="text-align: center; width: 112px">Status<br />Absen Siang</th>
                    <th style="text-align: center; width: 112px">Status<br />Absen Pulang</th>
                    <th style="text-align: center; width: 70px">Status</th>
                    <th style="text-align: center;">Keterangan</th>
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
                    <td style="text-align: center;"><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
                    <td style="text-align: center;"><?= $dayList[(new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('D')]; ?></td>
                    <td style="text-align: center;"><?= $data->jam_masuk ?></td>
                    <td style="text-align: center;"><?= $data->jam_siang ?></td>
                    <td style="text-align: center;"><?= $data->jam_keluar ?></td>
                    <td style="text-align: center;"><?= ($data->selisih_jam_masuk != '') ? $data->status_masuk . ' (' . $data->selisih_jam_masuk . ')' : $data->status_masuk ?></td>
                    <td style="text-align: center;"><?= $data->status_siang ?></td>
                    <td style="text-align: center;"><?= ($data->selisih_jam_keluar != '') ? $data->status_keluar . ' (' . $data->selisih_jam_keluar . ')' : $data->status_keluar ?></td>
                    <td style="text-align: center;"><?= $data->status ?></td>
                    <td style="text-align: center;"><?= $data->keterangan ?></td>
                </tr>
                <?php
                $total_selisih_masuk+=strtotime($data->selisih_jam_masuk);
                $total_selisih_keluar+=strtotime($data->selisih_jam_keluar);
                $total_kehadiran+=1;
                if ($data->jam_masuk != '' && $data->jam_siang != '' && $data->jam_keluar != '') $total_hadir+=1;
                ?>
                <?php endforeach; ?>
                <!--
                <tr style="background-color: #ccc">
                    <th colspan="5" style="text-align: center;">Total</th>
                    <th style="text-align: center;"><?= date('H:i:s', $total_selisih_masuk) ?></th>
                    <th></th>
                    <th style="text-align: center;"><?= date('H:i:s', $total_selisih_keluar) ?></th>
                    <th style="text-align: center;"><?= $total_hadir . '/' . $total_kehadiran ?></th>
                    <th></th>
                </tr>
                -->
            </table>
        </div>
        <div class="col-md-12" style="margin-top: 10px; page-break-inside: avoid;">
            <?php
                $time = new \DateTime('now', new \DateTimeZone(TIMEZONE));
                $now = $time->format('d-m-Y');
            ?>
            <table class="table">
                <tr>
                    <td>MANOKWARI, <?= $now ?></td>
                </tr>
                <tr>
                    <td><?= $unit->jab_pl ?></td>
                </tr>
                <tr>
                    <td style="height: 70px"></td>
                </tr>
                <tr>
                    <td><span style="text-decoration: underline;"><?= ($unit->ka_unit) ? $unit->kaUnit->nama : '...' ?></span></td>
                </tr>
                <tr>
                    <td>NIP: <?= ($unit->ka_unit) ? $unit->kaUnit->NIP : '...' ?></td>
                </tr>
            </table>
        </div>
    </div>
</section>