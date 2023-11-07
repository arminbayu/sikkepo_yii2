<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

?>

<section id="mu-contact" style="padding-top: 30px; min-height: 600px">
  <div class="container">
    <div class="row">
      <div class="mu-contact-area" style="border: 0px solid #000">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Proses Sukses!</h3></div>
        <div class="col-md-12">
        <?php
          $total_hari_kerja = 0;
          $nilai_kehadiran = $bobot_kehadiran = 60;
          $bobot_ketepatan = 20;
          $pengurangan_ketepatan = 0;
          $pengurangan_kehadiran = 0;
          $status = '';
          $mtw = 0;

          foreach ($model as $data) {
              $total_hari_kerja+=1;
          }
          if ($total_hari_kerja != 0) {

              $pengurangan_ketepatan = round(20/$total_hari_kerja, 2);

              // echo $bobot_kehadiran.'<br>';
              // echo '--------------------------------<br>';
              echo '<table border="1">';
              echo '<tr>';
              echo '<td>Tanggal</td>';
              echo '<td>NIP</td>';
              echo '<td>Masuk</td>';
              echo '<td>Siang</td>';
              echo '<td>Keluar</td>';
              echo '<td>Pengurangan KTP</td>';
              echo '<td>Pengurangan KH</td>';
              echo '<td>Bobot KTP</td>';
              echo '<td>Bobot KH</td>';
              echo '<td>Selisih Masuk</td>';
              echo '<td>Selisih Pagi - Siang</td>';
              echo '<td>Selisih Siang - Sore</td>';
              echo '<td>Status</td>';
              echo '</tr>';

              
              // $abc = round(60/$total_hari_kerja, 2);
              // echo 'xxx'.$abc.'xxx'.round($abc*$total_hari_kerja);
              // echo '<br>';


              foreach ($model as $data) {
                  // if (!$this->checkKeterangan($nip, $data->tanggal))
                  //     $keterangan = 0;
                  // else
                  //     $keterangan = $this->checkKeterangan($nip, $data->tanggal);

                  // echo $keterangan;
                      

                  if ($data->status == 'HD') {
                      $status = 'hadir';

                      if ($data->status_masuk == 'TW')
                          $mtw+=1;

                      if ($data->selisih_jam_masuk != Null) {
                          $bobot_ketepatan -= $pengurangan_ketepatan;
                      }
                  }
                  else {
                      // // tanpa keterangan
                      // if ($data->status_masuk == 'TA' && $data->status_siang == 'TA' && $data->status_keluar == 'TA') {
                      //     $pengurangan = 5;
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'tanpa keterangan';
                      // }
                      // // hanya absen pagi
                      // elseif ($data->status_siang == 'TA' && $data->status_keluar == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-1));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen pagi';
                      // }
                      // // hanya absen siang
                      // elseif ($data->status_masuk == 'TA' && $data->status_keluar == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-1));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen siang';
                      // }
                      // // hanya absen sore
                      // elseif ($data->status_masuk == 'TA' && $data->status_siang == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-1));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen sore';
                      // }
                      // // hanya absen pagi dan sore
                      // elseif ($data->status_siang == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-4));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen pagi dan sore';
                      // }
                      // // hanya absen pagi dan siang
                      // elseif ($data->status_keluar == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-3));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen pagi dan siang';
                      // }
                      // // hanya absen siang dan sore
                      // elseif ($data->status_masuk == 'TA') {
                      //     $pengurangan = 5 - (0.67 * (7.5-3));
                      //     $bobot_kehadiran -= $pengurangan;
                      //     $status = 'hanya absen siang dan sore';
                      // }

                      $bobot_ketepatan -= $pengurangan_ketepatan;

                      $jam_masuk = new \DateTime($data->jam_masuk);
                      $jam_siang = new \DateTime($data->jam_siang);
                      $jam_keluar = new \DateTime($data->jam_keluar);

                      

                      // tanpa keterangan
                      if ($data->jam_masuk == Null && $data->jam_siang == Null && $data->jam_keluar == Null) {
                          $pengurangan_kehadiran = round((7.5/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          //$this->context->checkKeterangan($params);
                          //Yii::$app->runAction('pegawai/checkKeterangan', $params);
                          // if ($check = $this->checkKeterangan($nip, $data->tanggal)) {
                          if ($check = $this->context->checkKeterangan($nip, $data->tanggal)) {
                              $status = $check->data->keterangan;
                          }
                          else {
                              $status = 'tidak absen';
                          }
                      }
                      // hanya absen pagi
                      elseif ($data->jam_siang == Null && $data->jam_keluar == Null) {
                          $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen pagi';
                      }
                      // hanya absen siang
                      elseif ($data->jam_masuk == Null && $data->jam_keluar == Null) {
                          $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen siang';
                      }
                      // hanya absen sore
                      elseif ($data->jam_masuk == Null && $data->jam_siang == Null) {
                          $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen sore';
                      }
                      // hanya absen pagi dan sore
                      elseif ($data->jam_siang == Null) {
                          $pengurangan_kehadiran = round(((7.5-4)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen pagi dan sore';
                      }
                      // hanya absen pagi dan siang
                      elseif ($data->jam_keluar == Null) {
                          $masuk = $jam_siang->diff($jam_masuk)->format("%H") + round($jam_siang->diff($jam_masuk)->format("%I")/60, 2);
                          $pengurangan_kehadiran = round(((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen pagi dan siang';
                      }
                      // hanya absen siang dan sore
                      elseif ($data->jam_masuk == Null) {
                          $masuk = $jam_keluar->diff($jam_siang)->format("%H") + round($jam_keluar->diff($jam_siang)->format("%I")/60, 2);
                          $pengurangan_kehadiran = round(((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100, 2);
                          $bobot_kehadiran -= $pengurangan_kehadiran;
                          $status = 'hanya absen siang dan sore';
                      }
                  }
                  
                  echo '<tr>';
                  echo '<td>'.$data->tanggal.'</td>';
                  echo '<td>'.$data->NIP.'</td>';
                  echo '<td>'.$data->jam_masuk.'('.$data->status_masuk.')'.'</td>';
                  echo '<td>'.$data->jam_siang.'('.$data->status_siang.')'.'</td>';
                  echo '<td>'.$data->jam_keluar.'('.$data->status_keluar.')'.'</td>';
                  echo '<td>';
                  echo ($data->status == 'HD') ? (($data->selisih_jam_masuk != Null) ? $pengurangan_ketepatan : 0) : $pengurangan_ketepatan;
                  echo '</td>';
                  echo '<td>';
                  echo ($data->status == 'HD') ? 0 : $pengurangan_kehadiran;
                  echo '</td>';
                  echo '<td>';
                  echo ($bobot_ketepatan < 0.1) ? floor($bobot_ketepatan) : $bobot_ketepatan;
                  echo '</td>';
                  echo '<td>'.$bobot_kehadiran.'</td>';
                  echo '<td>'.$data->selisih_jam_masuk.'</td>';
                  // selisih pagi siang
                  echo '<td>';
                  echo ($data->status == 'HD') ? '' : ($data->status_masuk != 'TA') ? (($data->status_siang == 'TA' && $data->status_keluar == 'TA') ? '' : $jam_siang->diff($jam_masuk)->format("%H:%I:%S")) : '';
                  echo '</td>';
                  // selisih siang sore
                  echo '<td>';
                  echo ($data->status == 'HD') ? '' : ($data->status_keluar != 'TA') ? (($data->status_masuk == 'TA' && $data->status_siang == 'TA') ? '' : $jam_keluar->diff($jam_siang)->format("%H:%I:%S")) : '';
                  echo '</td>';
                  echo '<td>'.$status.'</td>';
                  echo '<tr>';
              }
              echo '</table>';
              //echo date("H:m:s",strtotime('15:40:44')-strtotime('12:11:21'));

              //$diff = (new \DateTime('15:40:44'))->diff(new \DateTime('12:11:21'));
              // echo ((new \DateTime('15:40:44'))->diff(new \DateTime('12:11:21')))->format("%H");
              // echo ((new \DateTime('15:40:44'))->diff(new \DateTime('12:11:21')))->format("%I"); 
              // echo ((new \DateTime('15:40:44'))->diff(new \DateTime('12:11:21')))->format("%H") + round(((new \DateTime('15:40:44'))->diff(new \DateTime('12:11:21')))->format("%I")/60, 2);

              //echo date("H:m:s",strtotime('12:11:21')+strtotime('03:01:23'));
              echo '--------------------------------<br>';
              echo 'Bobot Kehadiran: '.$bobot_kehadiran . '<br>';
              echo 'Bobot Ketepatan: ';
              echo ($bobot_ketepatan < 0.1) ? floor($bobot_ketepatan) : $bobot_ketepatan;
              echo '<br>';
              echo '--------------------------------<br>';
              echo 'Hari kerja: '.$total_hari_kerja.'<br>';

              // A1
              $jumlah_mtw = ($mtw/$total_hari_kerja) * 20;
              echo 'Jumlah Tepat Waktu: '.round($jumlah_mtw, 2);
              echo '<br><br><br>';

              // $start_date = "01-".$month."-".$year;
              // $start_time = strtotime($start_date);
              // $end_time = strtotime("+1 month", $start_time);


              // for ($i=$start_time; $i<$end_time; $i+=86400) {
              //     echo date('Y-m-d', $i).'<br>';
              //     if 
              //     // if (!$this->checkLibur(date('Y-m-d', $i)) && date('D', $i) != 'Sat' && date('D', $i) != 'Sun') {
              //     //     if (!$this->checkDataAbsenPegawai($nip, date('Y-m-d', $i))) {
              //     //         if (!$this->inputDataAbsenPegawai($id, $nip, date('Y-m-d', $i), date('D', $i))) {
              //     //             $absen = new AbsenPegawai;
              //     //             $absen->id = 0;
              //     //             $absen->NIP = $nip;
              //     //             $absen->tanggal = date('Y-m-d', $i);
              //     //             $absen->status = 'TH';
              //     //             $absen->save();
              //     //         }
              //     //     }
              //     // }
              // }
          }
          else {
              echo 'Data belum ada.';
          }
        ?>
        </div>
      </div>
    </div>
  </div>
</section>
