<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Detail TPP Bulan <?= $monthList[$current_month] ?> Tahun <?= $current_year ?> <!--<?= Html::a('<i class="fa fa-print"></i> PDF', ['print-tpp-pegawai-pdf', 'nip'=>$nip, 'ym'=>$bulan], ['class' => 'btn-sm btn-primary pull-right', 'target' => '_blank', 'title'=>'Laporan dalam format PDF']) ?>--></h3></div>
        <div class="col-md-12">
            <?php
            $total_hari_kerja = 0;
            $nilai_kehadiran = $bobot_kehadiran = BOBOT_KEHADIRAN;
            $bobot_ketepatan = BOBOT_KETEPATAN;
            $pengurangan_ketepatan = 0;
            $pengurangan_kehadiran = 0;
            $status = '';
            $mtw = 0;
            $hadir = 0;

            foreach ($model as $data) {
                $total_hari_kerja+=1;
            }

            if ($total_hari_kerja != 0)
                $pengurangan_ketepatan = $bobot_ketepatan/$total_hari_kerja;

            ?>

            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle; width: 80px">TANGGAL</th>
                    <th style="text-align: center; vertical-align: middle;">MASUK</th>
                    <th style="text-align: center; vertical-align: middle;">SIANG</th>
                    <th style="text-align: center; vertical-align: middle;">KELUAR</th  >
                    <th style="text-align: center; vertical-align: middle;">PENGURANGAN KETEPATAN</th>
                    <th style="text-align: center; vertical-align: middle;">BOBOT KETEPATAN</th>
                    <th style="text-align: center; vertical-align: middle;">PENGURANGAN KEHADIRAN</th>
                    <th style="text-align: center; vertical-align: middle;">BOBOT KEHADIRAN</th>
                    <th style="text-align: center; vertical-align: middle;">SELISIH MASUK</th>
                    <th style="text-align: center; vertical-align: middle;">SELISIH PAGI SIANG</th>
                    <th style="text-align: center; vertical-align: middle;">SELISIH SIANG SORE</th>
                    <th style="text-align: center; vertical-align: middle;">STATUS</th>
                </tr>
                <?php foreach ($model as $data) : ?>
                <?php   
                if ($data->status == 'HD') {
                    $status = 'Hadir';
                    $hadir++;

                    if ($data->status_masuk == 'TW')
                        $mtw+=1;

                    if ($data->selisih_jam_masuk != Null) {
                        $bobot_ketepatan -= $pengurangan_ketepatan;
                    }
                }
                else {
                    $bobot_ketepatan -= $pengurangan_ketepatan;

                    $jam_masuk = new \DateTime($data->jam_masuk, new \DateTimeZone(TIMEZONE));
                    $jam_siang = new \DateTime($data->jam_siang, new \DateTimeZone(TIMEZONE));
                    $jam_keluar = new \DateTime($data->jam_keluar, new \DateTimeZone(TIMEZONE));

                    // tanpa keterangan
                    if ($data->jam_masuk == Null && $data->jam_siang == Null && $data->jam_keluar == Null) {
                        // round((7.5/7.5 * 5) * $nilai_kehadiran/100, 2)
                        // $pengurangan_kehadiran = round($nilai_kehadiran/$total_hari_kerja, 2);
                        // $bobot_kehadiran -= $pengurangan_kehadiran;
                        if ($check = $this->context->checkKeterangan($data->nip, $data->tanggal)) {
                            $status = $check->data->keterangan;
                            if ($status == 'Ijin') {
                                $pengurangan_kehadiran = 1.25 * $nilai_kehadiran/100;
                                $bobot_kehadiran -= $pengurangan_kehadiran;
                            }
                            elseif ($status == 'Sakit') {
                                $pengurangan_kehadiran = 1.25 * $nilai_kehadiran/100;
                                $bobot_kehadiran -= $pengurangan_kehadiran;
                            }
                            elseif ($status == 'Cuti Alasan Penting') {
                                $pengurangan_kehadiran = 1.5 * $nilai_kehadiran/100;
                                $bobot_kehadiran -= $pengurangan_kehadiran;
                            }
                            else {
                                $pengurangan_kehadiran = 0;
                                $bobot_kehadiran -= $pengurangan_kehadiran;
                            }
                        }
                        else {
                            $pengurangan_kehadiran = $nilai_kehadiran/$total_hari_kerja;
                            $bobot_kehadiran -= $pengurangan_kehadiran;
                            $status = 'Tidak Absen';
                        }
                    }
                    // hanya absen pagi
                    elseif ($data->jam_siang == Null && $data->jam_keluar == Null) {
                        $pengurangan_kehadiran = ((7.5-1)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Pagi';
                    }
                    // hanya absen siang
                    elseif ($data->jam_masuk == Null && $data->jam_keluar == Null) {
                        $pengurangan_kehadiran = ((7.5-1)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Siang';
                    }
                    // hanya absen sore
                    elseif ($data->jam_masuk == Null && $data->jam_siang == Null) {
                        $pengurangan_kehadiran = ((7.5-1)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Sore';
                    }
                    // hanya absen pagi dan sore
                    elseif ($data->jam_siang == Null) {
                        $pengurangan_kehadiran = ((7.5-4)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Pagi dan Sore';
                    }
                    // hanya absen pagi dan siang
                    elseif ($data->jam_keluar == Null) {
                        $masuk = $jam_siang->diff($jam_masuk)->format("%H") + round($jam_siang->diff($jam_masuk)->format("%I")/60, 2);
                        $pengurangan_kehadiran = ((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Pagi dan Siang';
                    }
                    // hanya absen siang dan sore
                    elseif ($data->jam_masuk == Null) {
                        $masuk = $jam_keluar->diff($jam_siang)->format("%H") + round($jam_keluar->diff($jam_siang)->format("%I")/60, 2);
                        $pengurangan_kehadiran = ((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100;
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Hanya Absen Siang dan Sore';
                    }
                }
                ?>
                <tr>
                    <td style="vertical-align: middle;"><?= (new \DateTime($data->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?= $data->jam_masuk.'('.$data->status_masuk.')' ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?= $data->jam_siang.'('.$data->status_siang.')' ?></td>
                    <td style="text-align: center; vertical-align: middle;"><?= $data->jam_keluar.'('.$data->status_keluar.')' ?></td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo ($data->status == 'HD') ? (($data->selisih_jam_masuk != Null) ? round($pengurangan_ketepatan, 2) : 0) : round($pengurangan_ketepatan, 2); ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo round(abs($bobot_ketepatan), 2); ?>
                    </td> 
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo ($data->status == 'HD') ? 0 : round($pengurangan_kehadiran, 2); ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo round(abs($bobot_kehadiran), 2); ?>
                    </td> 
                    <td style="text-align: center; vertical-align: middle;"><?= $data->selisih_jam_masuk ?></td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo ($data->status == 'HD') ? '' : ($data->status_masuk != 'TA') ? (($data->status_siang == 'TA' && $data->status_keluar == 'TA') ? '' : ($data->status_siang != 'TA' ? $jam_siang->diff($jam_masuk)->format("%H:%I:%S") : '')) : ''; ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <?php echo ($data->status == 'HD') ? '' : ($data->status_keluar != 'TA') ? (($data->status_masuk == 'TA' && $data->status_siang == 'TA') ? '' : ($data->status_siang != 'TA' ? $jam_keluar->diff($jam_siang)->format("%H:%I:%S") : '')) : ''; ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle;"><?= $status ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php
            // ($bobot_kehadiran < 0) ? $bobot_kehadiran = floor($bobot_kehadiran*(-1)) : $bobot_kehadiran;
            // ($bobot_ketepatan < 0.1) ? $bobot_ketepatan = floor($bobot_ketepatan) : $bobot_ketepatan;
            $bobot_kehadiran = round($bobot_kehadiran);
            $bobot_ketepatan = round($bobot_ketepatan);
            
            if ($mtw != 0)
                $jumlah_mtw = ($mtw/$total_hari_kerja) * BOBOT_KETEPATAN;
            else
                $jumlah_mtw = 0;

            ?>
            <!--
            <br>
            Hari kerja: <?= $total_hari_kerja ?>
            <br>
            Hadir: <?= $hadir ?>
            <br>
            Bobot Kehadiran: <?= $bobot_kehadiran ?>
            <br>
            Bobot Ketepatan: <?= $bobot_ketepatan ?>
            <br>
            Bobot Total PPK: <?php echo $bobot_kehadiran + $bobot_ketepatan; ?>
            <br>
            Bobot Kinerja: 20
            <br>
            Bobot Total TPP: <?php echo $bobot_kehadiran + $bobot_ketepatan + 20; ?>
            <br>

            Masuk Tepat Waktu: <?= $mtw ?>
            <br>
            Jumlah Tepat Waktu: <?= round($jumlah_mtw, 2) ?>
            -->
        </div>
    </div>
</section>