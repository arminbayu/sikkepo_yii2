<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\DataPegawai;
use common\models\AbsenPegawai;
use common\models\UnitKerja;
use common\models\Keterangan;
use common\models\PrestasiPerilakuKerja;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

ini_set('max_execution_time', -1);
ini_set('memory_limit', '1024M');

/**
 * TppController implements the CRUD actions for AbsenPegawai model.
 */
class TppController extends Controller
{
    public $layout = 'admin';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['tpp-per-unit-kerja', 'proses-tpp-per-unit-kerja', 'tpp-pegawai-per-unit-kerja', 'tpp-pegawai'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    public function actionTppPerUnitKerja()
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-1 month', $bulan);
        // $periode = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $periode = $bulan->format('Y-m');
        $bulan = $bulan->format('U');

        $model = UnitKerja::find()
            ->select([
                'unit_kerja.*',
                'jml_pegawai'=>'COUNT(data_pegawai.NIP)'])
            ->where(['data_pegawai.status'=>1])
            ->innerJoinWith(['dataPegawai'])
            ->groupBy('unit_kerja.kode')
            ->asArray()
            ->all();

        return $this->render('tpp-per-unit-kerja', [
            'model'=>$model,
            'periode'=>$periode,
        ]);
    }



    public function actionProsesTppPerUnitKerja($unit)
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-2 month', $bulan);
        // $ym = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');

        //siapkan data yang akan dihapus
        $tpp_pegawai = PrestasiPerilakuKerja::find()
            ->where(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->andWhere(['data_pegawai.unit_kerja'=>$unit])
            ->innerJoinWith(['nip'])
            ->all();

        // proses hapus data tpp pegawai
        // PrestasiPerilakuKerja::deleteAll(['IN', 'NIP', array_values($tpp_pegawai)]);

        PrestasiPerilakuKerja::deleteAll(['AND', 
            ['IN', 'NIP', array_values($tpp_pegawai)],
            ['IN', 'bulan', array_values($tpp_pegawai)]]
        );

        $model = AbsenPegawai::find()
            ->where(['unit_kerja'=>$unit, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->groupBy(['NIP'])
            ->all();

        foreach ($model as $data) {
            $this->actionSavePPK($data->NIP, $unit, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai. '.Yii::getLogger()->getElapsedTime());

        return $this->redirect(['tpp-per-unit-kerja']);
    }



    public function actionSavePPK($nip, $unit, $ym)
    {
        // $month = date('m', strtotime($ym));
        // $year = date('Y', strtotime($ym));

        $ym_ = \DateTime::createFromFormat('Y-m', $ym);
        $month = $ym_->format('m');
        $year = $ym_->format('Y');

        // $results = PrestasiPerilakuKerja::find()
        //     ->where(['NIP'=>$nip, 'DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
        //     ->one();

        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

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

        foreach ($model as $data) {
            $hari = (new \DateTime($data->tanggal))->format('D');

            if ($data->status_masuk == 'TW' || $data->keterangan == 'Dinas Luar' || $data->keterangan == 'Cuti Sakit'|| $data->keterangan == 'Cuti Tahunan' || $data->keterangan == 'Cuti Karena Alasan Penting' || $data->keterangan == 'Cuti Melahirkan' || $data->keterangan == 'Diklat Pimpinan' || $data->keterangan == 'Diklat Prajabatan' || $data->keterangan == 'Rekomendasi') {
                $mtw+=1;
            }
            else {
                $bobot_ketepatan -= $pengurangan_ketepatan;
            }
            
            if ($data->status == 'HD') {
                // jika datang terlambat
                // if ($data->status_masuk == 'TL') {
                //     $jam_pengurang = new \DateTime('00:00:00', new \DateTimeZone(TIMEZONE));
                //     $selisih_jam_masuk = new \DateTime($data->selisih_jam_masuk, new \DateTimeZone(TIMEZONE));
                    
                //     $masuk = $selisih_jam_masuk->diff($jam_pengurang)->format("%H") + round($selisih_jam_masuk->diff($jam_pengurang)->format("%I")/60, 2);
                //     $pengurangan_kehadiran = ((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100;
                //     $bobot_kehadiran -= $pengurangan_kehadiran;
                // }

                // jika pulang cepat
                if ($data->status_keluar == 'PC') {
                    $jam_pengurang = new \DateTime('00:00:00', new \DateTimeZone(TIMEZONE));
                    $selisih_jam_keluar = new \DateTime($data->selisih_jam_keluar, new \DateTimeZone(TIMEZONE));

                    $keluar = $selisih_jam_keluar->diff($jam_pengurang)->format("%H") + round($selisih_jam_keluar->diff($jam_pengurang)->format("%I")/60, 2);
                    $pengurangan_kehadiran = ((7.5-(7.5-$keluar))/7.5 * 5) * $nilai_kehadiran/100;
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                }

                $status = 'Hadir';
                $hadir++;
            }
            else {
                $jam_masuk = new \DateTime($data->jam_masuk, new \DateTimeZone(TIMEZONE));
                $jam_siang = new \DateTime($data->jam_siang, new \DateTimeZone(TIMEZONE));

                if ($hari == 'Fri') {
                    $jam_keluar = ((new \DateTime($data->jam_keluar, new \DateTimeZone(TIMEZONE))) > (new \DateTime(MIN_KELUAR_FRIDAY, new \DateTimeZone(TIMEZONE)))) ? (new \DateTime(MIN_KELUAR_FRIDAY, new \DateTimeZone(TIMEZONE))) : (new \DateTime($data->jam_keluar, new \DateTimeZone(TIMEZONE)));
                }
                else {
                    $jam_keluar = ((new \DateTime($data->jam_keluar, new \DateTimeZone(TIMEZONE))) > (new \DateTime(MIN_KELUAR, new \DateTimeZone(TIMEZONE)))) ? (new \DateTime(MIN_KELUAR, new \DateTimeZone(TIMEZONE))) : (new \DateTime($data->jam_keluar, new \DateTimeZone(TIMEZONE)));
                }

                // tanpa keterangan
                if ($data->jam_masuk == Null && $data->jam_siang == Null && $data->jam_keluar == Null) {
                    // round((7.5/7.5 * 5) * $nilai_kehadiran/100, 2)
                    // $pengurangan_kehadiran = round($nilai_kehadiran/$total_hari_kerja, 2);
                    if ($check = $this->checkKeterangan($nip, $data->tanggal)) {
                        $status = $check->data->keterangan;
                        if ($status == 'Ijin') {
                            $pengurangan_kehadiran = 1.25 * $nilai_kehadiran / 100;
                        }
                        elseif ($status == 'Cuti Besar' || $status == 'Cuti Diluar Tanggungan Negara' || $status == 'Tugas Belajar') {
                            // $pengurangan_kehadiran = $nilai_kehadiran/$total_hari_kerja;
                            $pengurangan_kehadiran = 5 * $nilai_kehadiran / 100;
                        }
                        else {
                            $pengurangan_kehadiran = 0;
                        }
                    }
                    else {
                        $pengurangan_kehadiran = 5 * $nilai_kehadiran / 100;
                        $status = 'Tidak Absen';
                    }
                    $bobot_kehadiran -= $pengurangan_kehadiran;
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
        }

        // ($bobot_kehadiran < 0) ? $bobot_kehadiran = floor($bobot_kehadiran*(-1)) : $bobot_kehadiran;
        // ($bobot_ketepatan < 0.1) ? $bobot_ketepatan = floor($bobot_ketepatan) : $bobot_ketepatan;
        $bobot_kehadiran = ($bobot_kehadiran > 0) ? round($bobot_kehadiran, 2) : 0;
        $bobot_ketepatan = round(abs($bobot_ketepatan), 2);
        $bobot_kinerja = 0;

        // $bobot_kinerja = round(JAM_KINERJA/JAM_KINERJA*BOBOT_KINERJA, 2);

        // if (count($results) == 0) {
        //     $ppk = new PrestasiPerilakuKerja;
        //     $ppk->id = 0;
        //     $ppk->NIP = $nip;
        //     $ppk->bulan = $ym.'-00';
        //     $ppk->jumlah_hari_kerja = $total_hari_kerja;
        //     $ppk->hadir = $hadir;
        //     $ppk->bobot_kehadiran = $bobot_kehadiran;
        //     $ppk->bobot_ketepatan = $bobot_ketepatan;
        //     $ppk->jumlah_bobot_ppk = $bobot_kehadiran + $bobot_ketepatan;
        //     $ppk->bobot_kinerja = JAM_KINERJA;
        //     $ppk->jumlah_total = $bobot_kehadiran + $bobot_ketepatan + $bobot_kinerja;
        //     $ppk->save();
        // }
        // else {
        try {
            if (!$this->checkPPKPegawai($nip, $ym)) {
                $ppk = new PrestasiPerilakuKerja;
                $ppk->id = 0;
                $ppk->NIP = $nip;
                $ppk->gol = $ppk->nip->gol_ruang;
                $ppk->unit_kerja = $unit;
                $ppk->bulan = $ym.'-01';
                $ppk->jumlah_hari_kerja = $total_hari_kerja;
                $ppk->hadir = $hadir;
                $ppk->bobot_kehadiran = ($hadir == 0 && $bobot_ketepatan == 0 ) ? 0 : $bobot_kehadiran;
                $ppk->bobot_ketepatan = $bobot_ketepatan;
                $ppk->jumlah_bobot_ppk = $bobot_kehadiran + $bobot_ketepatan;
                if ($check = $this->checkKeteranganKTH($nip, $ym)) {
                    $status = $check->data->keterangan;
                    if ($status == 'Tugas Belajar') {
                        $ppk->bobot_kinerja = JAM_KINERJA;
                    }
                    else {
                        $ppk->bobot_kinerja = 0;
                    }
                }
                else {
                    $ppk->bobot_kinerja = 0;
                }
                $ppk->jumlah_total = $bobot_kehadiran + $bobot_ketepatan + $bobot_kinerja;
                $ppk->tpp = $ppk->nip->kodeTpp->tpp;
                $gol = explode('/', $ppk->nip->gol_ruang);
                $tpp_sebelum_pajak = ($ppk->nip->kodeTpp) ? (($bobot_ketepatan + $bobot_kehadiran + $bobot_kinerja)/100) * $ppk->nip->kodeTpp->tpp : '';
                $ppk->tpp_sebelum_pajak = $tpp_sebelum_pajak;
                $pajak = ($data->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 : (($gol[0] == 'IV') ? 15/100 : 0) : '';
                $ppk->pajak = $pajak;
                $pajak_tpp = $tpp_sebelum_pajak * $pajak;
                // $pajak_tpp = ($ppk->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 * (($bobot_ketepatan + $bobot_kehadiran + $bobot_kinerja)/100) * $ppk->nip->kodeTpp->tpp : (($gol[0] == 'IV') ? 15/100 * (($bobot_ketepatan + $bobot_kehadiran + $bobot_kinerja)/100) * $ppk->nip->kodeTpp->tpp : 0) : '';

                $round_pajak_tpp = round($pajak_tpp);
                $ppk->pajak_tpp = $round_pajak_tpp;
                $tpp_dibayar = $tpp_sebelum_pajak - $round_pajak_tpp;
                $ppk->tpp_final = $tpp_dibayar;
                $ppk->save();   
            }
        }
        catch (yii\db\IntegrityException $e) {
            //
        }
        // }
        
    }



    public function actionTppPegawaiPerUnitKerja($unit)
    {
        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');

        $model = PrestasiPerilakuKerja::find()
            ->where(['data_pegawai.unit_kerja'=>$unit])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        if (Model::loadMultiple($model, Yii::$app->request->post()) && Model::validateMultiple($model)) {
            foreach ($model as $kinerja) {
                $kinerja->save(false);
            }
            return $this->redirect(['tpp-pegawai-per-unit-kerja', 'unit'=>$unit->kode]);
        }

        return $this->render('tpp-pegawai-per-unit-kerja', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);
    }



    public function actionTppPegawai($nip)
    {
        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');

        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        return $this->render('tpp-pegawai', [
            'model'=>$model,
            'pegawai'=>$data,
            'bulan'=>$ym,
        ]);        
    }



    public function checkKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
        return $data;
    }



    public function checkKeteranganKTH($nip, $ym) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])->one();
        return $data;
    }



    public function checkPPKPegawai($nip, $ym) {
        $data = PrestasiPerilakuKerja::find()
            ->where(['NIP'=>$nip])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            // ->innerJoinWith(['nip'])
            ->exists();
        return $data;
    }



    public function checkDataTppPegawai($unit, $ym) {
        $data = PrestasiPerilakuKerja::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            // ->innerJoinWith(['nip'])
            ->exists();
        return $data;
    }



    public function checkDataAbsenPegawai($unit, $ym) {
        $data = AbsenPegawai::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->exists();
        return $data;
    }







































































    public function actionSavePPK2($nip, $ym)
    {
        $month = date('m', strtotime($ym));
        $year = date('Y', strtotime($ym));

        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $total_hari_kerja = 0;
        $nilai_kehadiran = $bobot_kehadiran = 60;
        $bobot_ketepatan = 20;
        $pengurangan_ketepatan = 0;
        $pengurangan_kehadiran = 0;
        $status = '';
        $mtw = 0;
        $hadir = 0;

        foreach ($model as $data) {
            $total_hari_kerja+=1;
        }

        $pengurangan_ketepatan = round($bobot_ketepatan/$total_hari_kerja, 2);

        // echo '<table border="1">';
        // echo '<tr>';
        // echo '<td>Tanggal</td>';
        // echo '<td>NIP</td>';
        // echo '<td>Masuk</td>';
        // echo '<td>Siang</td>';
        // echo '<td>Keluar</td>';
        // echo '<td>Pengurangan Ketepatan</td>';
        // echo '<td>Bobot Ketepatan</td>';
        // echo '<td>Pengurangan Kehadiran</td>';
        // echo '<td>Bobot Kehadiran</td>';
        // echo '<td>Selisih Masuk</td>';
        // echo '<td>Selisih Pagi - Siang</td>';
        // echo '<td>Selisih Siang - Sore</td>';
        // echo '<td>Status</td>';
        // echo '</tr>';

        foreach ($model as $data) {   
            if ($data->status == 'HD') {
                $status = 'hadir';
                $hadir++;

                if ($data->status_masuk == 'TW')
                    $mtw+=1;

                if ($data->selisih_jam_masuk != Null) {
                    $bobot_ketepatan -= $pengurangan_ketepatan;
                }
            }
            else {
                $bobot_ketepatan -= $pengurangan_ketepatan;

                $jam_masuk = new \DateTime($data->jam_masuk);
                $jam_siang = new \DateTime($data->jam_siang);
                $jam_keluar = new \DateTime($data->jam_keluar);

                // tanpa keterangan
                if ($data->jam_masuk == Null && $data->jam_siang == Null && $data->jam_keluar == Null) {
                    // round((7.5/7.5 * 5) * $nilai_kehadiran/100, 2)
                    // $pengurangan_kehadiran = round($nilai_kehadiran/$total_hari_kerja, 2);
                    // $bobot_kehadiran -= $pengurangan_kehadiran;
                    if ($check = $this->checkKeterangan($nip, $data->tanggal)) {
                        $status = $check->data->keterangan;
                        if ($status == 'Ijin') {
                            $pengurangan_kehadiran = round(1.25 * $nilai_kehadiran/100, 2);
                            $bobot_kehadiran -= $pengurangan_kehadiran;
                        }
                        elseif ($status == 'Sakit') {
                            $pengurangan_kehadiran = round(1.25 * $nilai_kehadiran/100, 2);
                            $bobot_kehadiran -= $pengurangan_kehadiran;
                        }
                        elseif ($status == 'Cuti Alasan Penting') {
                            $pengurangan_kehadiran = round(1.5 * $nilai_kehadiran/100, 2);
                            $bobot_kehadiran -= $pengurangan_kehadiran;
                        }
                        else {
                            $pengurangan_kehadiran = 0;
                            $bobot_kehadiran -= $pengurangan_kehadiran;
                        }
                    }
                    else {
                        $pengurangan_kehadiran = round($nilai_kehadiran/$total_hari_kerja, 2);
                        $bobot_kehadiran -= $pengurangan_kehadiran;
                        $status = 'Tidak Absen';
                    }
                }
                // hanya absen pagi
                elseif ($data->jam_siang == Null && $data->jam_keluar == Null) {
                    $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Pagi';
                }
                // hanya absen siang
                elseif ($data->jam_masuk == Null && $data->jam_keluar == Null) {
                    $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Siang';
                }
                // hanya absen sore
                elseif ($data->jam_masuk == Null && $data->jam_siang == Null) {
                    $pengurangan_kehadiran = round(((7.5-1)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Sore';
                }
                // hanya absen pagi dan sore
                elseif ($data->jam_siang == Null) {
                    $pengurangan_kehadiran = round(((7.5-4)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Pagi dan Sore';
                }
                // hanya absen pagi dan siang
                elseif ($data->jam_keluar == Null) {
                    $masuk = $jam_siang->diff($jam_masuk)->format("%H") + round($jam_siang->diff($jam_masuk)->format("%I")/60, 2);
                    $pengurangan_kehadiran = round(((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Pagi dan Siang';
                }
                // hanya absen siang dan sore
                elseif ($data->jam_masuk == Null) {
                    $masuk = $jam_keluar->diff($jam_siang)->format("%H") + round($jam_keluar->diff($jam_siang)->format("%I")/60, 2);
                    $pengurangan_kehadiran = round(((7.5-$masuk)/7.5 * 5) * $nilai_kehadiran/100, 2);
                    $bobot_kehadiran -= $pengurangan_kehadiran;
                    $status = 'Hanya Absen Siang dan Sore';
                }
            }
            
            // echo '<tr>';
            // echo '<td>'.$data->tanggal.'</td>';
            // echo '<td>'.$data->NIP.'</td>';
            // echo '<td>'.$data->jam_masuk.'('.$data->status_masuk.')'.'</td>';
            // echo '<td>'.$data->jam_siang.'('.$data->status_siang.')'.'</td>';
            // echo '<td>'.$data->jam_keluar.'('.$data->status_keluar.')'.'</td>';
            // echo '<td>';
            // echo ($data->status == 'HD') ? (($data->selisih_jam_masuk != Null) ? $pengurangan_ketepatan : 0) : $pengurangan_ketepatan;
            // echo '</td>';
            // echo '<td>';
            // echo ($bobot_ketepatan < 0.1) ? floor($bobot_ketepatan) : $bobot_ketepatan;
            // echo '</td>';
            // echo '<td>';
            // echo ($data->status == 'HD') ? 0 : $pengurangan_kehadiran;
            // echo '</td>';
            // echo '<td>';
            // echo ($bobot_kehadiran < 0) ? floor($bobot_kehadiran*(-1)) : $bobot_kehadiran;
            // echo '</td>';
            // echo '<td>'.$data->selisih_jam_masuk.'</td>';
            // // selisih pagi siang
            // echo '<td>';
            // echo ($data->status == 'HD') ? '' : ($data->status_masuk != 'TA') ? (($data->status_siang == 'TA' && $data->status_keluar == 'TA') ? '' : ($data->status_siang != 'TA' ? $jam_siang->diff($jam_masuk)->format("%H:%I:%S") : '')) : '';
            // echo '</td>';
            // // selisih siang sore
            // echo '<td>';
            // echo ($data->status == 'HD') ? '' : ($data->status_keluar != 'TA') ? (($data->status_masuk == 'TA' && $data->status_siang == 'TA') ? '' : ($data->status_siang != 'TA' ? $jam_keluar->diff($jam_siang)->format("%H:%I:%S") : '')) : '';
            // echo '</td>';
            // echo '<td>'.$status.'</td>';
            // echo '<tr>';
        }
        // echo '</table>';

        ($bobot_kehadiran < 0) ? $bobot_kehadiran = floor($bobot_kehadiran*(-1)) : $bobot_kehadiran;
        ($bobot_ketepatan < 0.1) ? $bobot_ketepatan = floor($bobot_ketepatan) : $bobot_ketepatan;

        // echo '<br>';
        // echo 'Bulan: '.Yii::$app->getRequest()->getQueryParam('m').'<br>';
        // echo 'NIP: '.$nip.'<br>';
        // echo 'Hari kerja: '.$total_hari_kerja.'<br>';
        // echo 'Hadir: '.$hadir.'<br>';
        // echo 'Bobot Kehadiran: '.$bobot_kehadiran.'<br>';
        // echo 'Bobot Ketepatan: '.$bobot_ketepatan.'<br>';
        // echo 'Bobot Total PPK: ';
        // echo $bobot_kehadiran + $bobot_ketepatan;
        // echo '<br>';
        // echo 'Bobot Kinerja: '. 20 .'<br>';
        // echo 'Bobot Total TPP: ';
        // echo $bobot_kehadiran + $bobot_ketepatan + 20;
        // echo '<br>';
        // echo '--------------------------------<br>';
        
        // A1
        // $jumlah_mtw = ($mtw/$total_hari_kerja) * 20;
        // echo 'Masuk Tepat Waktu: '.$mtw;
        // echo '<br>';
        // echo 'Jumlah Tepat Waktu: '.round($jumlah_mtw, 2);
        // echo '<br><br><br>';

        $ppk = new PrestasiPerilakuKerja;
        $ppk->id = 0;
        $ppk->NIP = $nip;
        $ppk->bulan = Yii::$app->getRequest()->getQueryParam('m');
        $ppk->jumlah_hari_kerja = $total_hari_kerja;
        $ppk->hadir = $hadir;
        $ppk->bobot_kehadiran = $bobot_kehadiran;
        $ppk->bobot_ketepatan = $bobot_ketepatan;
        $ppk->jumlah_bobot_ppk = $bobot_kehadiran + $bobot_ketepatan;
        $ppk->bobot_kinerja = 20;
        $ppk->jumlah_total = $bobot_kehadiran + $bobot_ketepatan + 20;
        $ppk->save();
        
    }




    // public function checkDataTppPegawai($unit, $ym) {
    //     $data = AbsenPegawai::find()
    //         ->where(['unit_kerja'=>$unit])
    //         ->andWhere(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
    //         ->innerJoinWith(['nip'])
    //         ->exists();
    //     return $data;
    // }




    
}
