<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\DataAbsenManual;
use common\models\AbsenPegawai;
use common\models\AbsenPegawaiClone;
use common\models\UnitKerja;
use common\models\HariLibur;
use common\models\Keterangan;
use common\models\TugasLuar;
use common\models\Upacara;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

ini_set('max_execution_time', -1);
ini_set('memory_limit', '-1');

/**
 * KehadiranController implements the CRUD actions for AbsenPegawai model.
 */
class KehadiranController extends Controller
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
                        'actions' => ['upacara', 'upacara-pegawai', 'edit-upacara', 'edit-upacara-pegawai', 'ketidakhadiran', 'input-ketidakhadiran', 'edit-ketidakhadiran', 'edit-ketidakhadiran-pegawai', 'delete-ketidakhadiran', 'tugas-luar', 'input-tugas-luar', 'edit-tugas-luar', 'edit-tugas-luar-pegawai', 'delete-tugas-luar', 'absen-manual', 'absen-manual-pegawai', 'kehadiran-per-unit-kerja', 'proses-kehadiran-per-unit-kerja', 'kehadiran-pegawai-per-unit-kerja', 'lihat-kehadiran'],
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



    public function actionUpacara()
    {
        $status = 0;

        return $this->render('upacara', [
            'status'=>$status,
        ]);
    }



    public function actionUpacaraPegawai($unit, $ymd)
    {
        if ($ymd == '')
            return $this->redirect(['upacara', 'status' => 404]);

        $model = DataPegawai::find()
          ->where(['unit_kerja'=>$unit, 'status'=>1])
          ->all();

        $upacara = [];

        foreach ($model as $model) {
            $upacara[$model->NIP] = new Upacara();
        }

        if (Model::loadMultiple($upacara, Yii::$app->request->post()) && Model::validateMultiple($upacara)) {
            foreach ($upacara as $upacara) {
                if (!$this->checkDataUpacara($upacara->NIP, $upacara->tanggal)) {
                    $upacara->save(false);
                }
            }
            Yii::$app->session->setFlash('success', 'Data telah disimpan!');
            return $this->redirect(['upacara']);
        }

        return $this->render('upacara-pegawai', [
            'model' => $model,
            'upacara' => $upacara,
            'tanggal' => $ymd,
        ]);
    }



    public function actionEditUpacara()
    {
        return $this->render('edit-upacara');
    }



    public function actionEditUpacaraPegawai($nip, $ymd)
    {
        $model = Upacara::find()
          ->where(['NIP'=>$nip, 'tanggal'=>$ymd])
          ->one();

        if ($model && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah!');
            return $this->redirect(['edit-upacara']);
        } else {
            return $this->render('edit-upacara-pegawai', [
                'model' => $model,
            ]);
        }
    }



    public function actionKetidakhadiran()
    {
        return $this->render('ketidakhadiran');
    }



    public function actionInputKetidakhadiran()
    {
        $dari = Yii::$app->request->post('dari');
        $sampai = Yii::$app->request->post('sampai');

        if (Yii::$app->request->post('dari') == '' || Yii::$app->request->post('nip') == '' || Yii::$app->request->post('keterangan') == '') {
            return $this->redirect(['ketidakhadiran', 'status' => 404]);
        }
        else {
            // $start_time = strtotime(Yii::$app->request->post('dari'));
            $start_time = new \DateTime($dari, new \DateTimeZone(TIMEZONE));
            $start_time = $start_time->format('U');

            if ($sampai == '') {
                // $end_time = strtotime(Yii::$app->request->post('dari'));
                $dari = new \DateTime($dari, new \DateTimeZone(TIMEZONE));
                $end_time = $dari->format('U');
            }
            else {
                // $end_time = strtotime(Yii::$app->request->post('sampai'));
                $sampai = new \DateTime($sampai, new \DateTimeZone(TIMEZONE));
                $end_time = $sampai->format('U');
            }

            for ($tanggal=$start_time; $tanggal<=$end_time; $tanggal+=86400) {
                $tgl = \DateTime::createFromFormat('U', $tanggal);
                $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                // if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                    if (!$this->checkDataKeterangan(Yii::$app->request->post('nip'), $tgl->format('Y-m-d'))) {
                        $ket = new Keterangan;
                        $ket->id = 0;
                        $ket->NIP = Yii::$app->request->post('nip');
                        $ket->no_sk = Yii::$app->request->post('no_sk');
                        // $ket->tanggal = date('Y-m-d', $tanggal);
                        $ket->tanggal = $tgl->format('Y-m-d');
                        $ket->keterangan = Yii::$app->request->post('keterangan');
                        $ket->save();
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Data telah disimpan!');

            return $this->redirect(['ketidakhadiran', 'status' => 1]);
        }
    }



    public function actionEditKetidakhadiran()
    {
        return $this->render('edit-ketidakhadiran');
    }



    public function actionEditKetidakhadiranPegawai($nip, $ymd)
    {
        $model = Keterangan::find()
          ->where(['NIP'=>$nip, 'tanggal'=>$ymd])
          ->one();

        if ($model && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah!');
            return $this->redirect(['edit-ketidakhadiran']);
        } else {
            return $this->render('edit-ketidakhadiran-pegawai', [
                'model' => $model,
            ]);
        }
    }



    public function actionDeleteKetidakhadiran($id)
    {
        $this->findModelKeterangan($id)->delete();

        return $this->redirect(['edit-ketidakhadiran']);
    }



    public function actionTugasLuar()
    {
        return $this->render('tugas-luar');
    }



    public function actionInputTugasLuar()
    {
        $nip = Yii::$app->request->post('nip');
        $tanggal = Yii::$app->request->post('tanggal');
        $dari_jam = Yii::$app->request->post('dari_jam');
        $sampai_jam = Yii::$app->request->post('sampai_jam');

        $tgl = new \DateTime($tanggal, new \DateTimeZone(TIMEZONE));
        $tgl = $tgl->format('U');
        $tgl = \DateTime::createFromFormat('U', $tgl);
        $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

        $dari_jam_u = new \DateTime($dari_jam, new \DateTimeZone(TIMEZONE));
        $dari_jam_u = $dari_jam_u->format('U');

        $sampai_jam_u = new \DateTime($sampai_jam, new \DateTimeZone(TIMEZONE));
        $sampai_jam_u = $sampai_jam_u->format('U');

        if ($tanggal == '' || $dari_jam == '' || $sampai_jam == '' || $nip == '') {
            return $this->redirect(['tugas-luar', 'status' => 404]);
        }
        else {
            if ($sampai_jam_u > $dari_jam_u) {
                if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                    if (!$this->checkDataKeterangan($nip, $tgl->format('Y-m-d')) && !$this->checkDataTugasLuar($nip, $tgl->format('Y-m-d'))) {
                        $tl = new TugasLuar;
                        $tl->id = 0;
                        $tl->NIP = $nip;
                        $tl->no_surat = Yii::$app->request->post('no_surat');
                        $tl->tanggal = $tanggal;
                        $tl->dari_jam = $dari_jam . ':00';
                        $tl->sampai_jam = $sampai_jam . ':00';
                        $tl->keterangan = Yii::$app->request->post('keterangan');
                        $tl->save(); 

                        Yii::$app->session->setFlash('success', 'Data telah disimpan!');
                    }
                    Yii::$app->session->setFlash('failed', 'Data sudah ada!');
                }

                return $this->redirect(['tugas-luar', 'status' => 1]);
            }
            else {
                Yii::$app->session->setFlash('failed', 'Jam Selesai tidak boleh sama atau kurang dari Jam Mulai!');
                return $this->redirect(['tugas-luar', 'status' => 404]);
            }
        }

        // $jam_masuk = new \DateTime(MAX_MASUK, new \DateTimeZone(TIMEZONE));
        // $jam_masuk = $jam_masuk->format('U');

        // $jam_siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
        // $jam_siang = $jam_siang->format('U');

        // $jam_pulang = new \DateTime(MIN_KELUAR, new \DateTimeZone(TIMEZONE));
        // $jam_pulang = $jam_pulang->format('U');

        // $absen_pagi = \DateTime::createFromFormat('U', $jam_masuk);
        // $absen_pagi->setTimezone(new \DateTimeZone(TIMEZONE));

        // echo $absen_pagi->format('H:i:s');
        // echo '<br>';

        // // // // start code
        // $start_time = $dari_jam . ':00';
        // echo $start_time;
        // echo '<br>';
        // $start_time = new \DateTime($start_time, new \DateTimeZone(TIMEZONE));
        // $start_time = $start_time->format('U');
        // echo $start_time;
        // echo '<br>';
        // $absen_pagi = \DateTime::createFromFormat('U', $start_time);
        // $absen_pagi->setTimezone(new \DateTimeZone(TIMEZONE));

        // echo $absen_pagi->format('H:i:s');
        // echo '<br>';

        // $end_time = $sampai_jam . ':00';
        // echo $end_time;
        // $end_time = new \DateTime($end_time, new \DateTimeZone(TIMEZONE));
        // $end_time = $end_time->format('U');
        // echo $end_time;
        // echo '<br>';
        // // // // end code

        // if ($start_time <= $jam_masuk) {
        //     echo 'hadir pagi';
        // }
        // elseif (($start_time > $jam_masuk) && ($start_time <= $jam_siang)) {
        //     echo 'terlambat';
        // }
        // else {
        //     // $end_time = strtotime(Yii::$app->request->post('sampai'));
        //     $sampai = new \DateTime($sampai, new \DateTimeZone(TIMEZONE));
        //     $end_time = $sampai->format('U');
        // }
    }



    public function actionEditTugasLuar()
    {
        return $this->render('edit-tugas-luar');
    }



    public function actionEditTugasLuarPegawai($nip, $ymd)
    {
        $model = TugasLuar::find()
          ->where(['NIP'=>$nip, 'tanggal'=>$ymd])
          ->one();

        if ($model && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Data berhasil diubah!');
            return $this->redirect(['edit-tugas-luar']);
        } else {
            return $this->render('edit-tugas-luar-pegawai', [
                'model' => $model,
            ]);
        }
    }



    public function actionDeleteTugasLuar($id)
    {
        $this->findModelTugasLuar($id)->delete();

        return $this->redirect(['edit-tugas-luar']);
    }



    public function actionAbsenManual()
    {
        return $this->render('absen-manual');
    }



    public function actionAbsenManualPegawai($unit, $ymd, $absen, $keterangan)
    {
        if ($ymd == '')
            return $this->redirect(['absen-manual', 'status' => 404]);

        $model = DataPegawai::find()
          ->where(['unit_kerja'=>$unit, 'status'=>1])
          ->all();

        $absen_manual = [];

        foreach ($model as $model) {
            $absen_manual[$model->NIP] = new DataAbsenManual();
        }

        if (Model::loadMultiple($absen_manual, Yii::$app->request->post()) && Model::validateMultiple($absen_manual)) {
            foreach ($absen_manual as $absen_manual) {
                if (!$this->checkDataAbsenManual($absen_manual->NIP, $absen_manual->tanggal)) {
                    if ($absen_manual->jam != '0:00') {
                        $absen_manual->save(false);
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Data telah disimpan!');
            return $this->redirect(['absen-manual']);
        }

        return $this->render('absen-manual-pegawai', [
            'model' => $model,
            'absen_manual' => $absen_manual,
            'tanggal' => $ymd,
            'absen' => $absen,
            'keterangan' => $keterangan,
        ]);
    }



    public function actionKehadiranPerUnitKerja()
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-1 month', $bulan);
        // $periode = date('Y-m', $bulan);

        // echo 'Memory in use: ' . memory_get_usage() . ' ('. memory_get_usage()/1024/1024 .'M) <br>';
        // echo 'Peak usage: ' . memory_get_peak_usage() . ' ('. memory_get_peak_usage()/1024/1024 .'M) <br>';
        // echo 'Memory limit: ' . ini_get('memory_limit') . '<br>';

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $periode = $bulan->format('Y-m');
        $bulan = $bulan->format('U');

        $model = UnitKerja::find()->all();

        return $this->render('kehadiran-per-unit-kerja', [
            'model'=>$model,
            'periode'=>$periode,
        ]);
    }



    public function actionProsesKehadiranPerUnitKerja($unit)
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-2 month', $bulan);
        // $ym = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');

        //siapkan data yang akan dihapus
        $absen_pegawai = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->andWhere(['data_pegawai.unit_kerja'=>$unit])
            ->innerJoinWith(['nip'])
            ->all();

        // proses hapus data absen pegawai
        // AbsenPegawai::deleteAll(['IN', 'NIP', array_values($absen_pegawai)]);

        AbsenPegawai::deleteAll(['AND', 
            ['IN', 'NIP', array_values($absen_pegawai)],
            ['IN', 'tanggal', array_values($absen_pegawai)]]
        );

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiran($data->NIP, $data->kode_terminal, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai. '.gmdate('H:i:s', Yii::getLogger()->getElapsedTime()) . '<br />' . 'Memory in use: ' . round((memory_get_usage()/1024/1024), 2) . 'M<br />' . 'Peak usage: ' . round((memory_get_peak_usage()/1024/1024), 2) . 'M');

        return $this->redirect(['kehadiran-per-unit-kerja', 'unit' => $unit]);
    }



    public function actionProsesKehadiran($nip, $kode_terminal, $no_absen, $ym)
    {
        // $month = date('m', strtotime($ym));
        // $year = date('Y', strtotime($ym));

        $ym_ = \DateTime::createFromFormat('Y-m', $ym);
        $month = $ym_->format('m');
        $year = $ym_->format('Y');

        // $results = AbsenPegawai::find()
        //     ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
        //     ->all();

        // $start_date = "01-".$month."-".$year;
        // $start_time = strtotime($start_date);
        // $end_time = strtotime("+1 month", $start_time);

        $start_date = $ym_->format('Y-m-01');
        $start_time = new \DateTime($start_date, new \DateTimeZone(TIMEZONE));
        $start = $start_time->format('U');

        $end_time = $start_time->add(new \DateInterval('P1M'));
        $end = $end_time->format('U');

        // echo $start_time->format('Y-m-d').'<br>';
        // echo $end_time->format('Y-m-d').'<br>';

        $date = new \DateTime(null, new \DateTimeZone(TIMEZONE));
        $todays_month = $date->format('m');

        if ($month == $todays_month) {
            // $end_time = strtotime(date('d').'-'.$month."-".$year);
            $end = $date->format('U');

            for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                $tgl = \DateTime::createFromFormat('U', $tanggal);
                $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                // if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                    // if (!$this->checkAbsenPegawai($nip, date('Y-m-d', $tanggal))) {
                    if (!$this->checkAbsenPegawai($nip, $tgl->format('Y-m-d'))) {
                        // if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                        if (!$this->inputAbsenPegawai($kode_terminal, $no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'), $ym)) {
                            $absen = new AbsenPegawai;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = $tgl->format('Y-m-d');
                            $absen->status = 'TH';
                            if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                                $keterangan = $check->data->keterangan;
                            }
                            else {
                                $keterangan = 'Tanpa Keterangan';
                            }
                            $absen->keterangan = $keterangan;
                            $absen->save();
                        }
                    }
                }
            }
        }
        else {
            // if (count($results) == 0) {
                // for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                //     $tgl = \DateTime::createFromFormat('U', $tanggal);
                //     $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                //     // if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                //     if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                //         // if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                //         if (!$this->inputAbsenPegawai($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
                //             $absen = new AbsenPegawai;
                //             $absen->id = 0;
                //             $absen->NIP = $nip;
                //             $absen->tanggal = $tgl->format('Y-m-d');
                //             $absen->status_masuk = 'TA';
                //             $absen->status_siang = 'TA';
                //             $absen->status_keluar = 'TA';
                //             $absen->status = 'TH';
                //             if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                //                 $keterangan = $check->data->keterangan;
                //             }
                //             else {
                //                 $keterangan = 'Tanpa Keterangan';
                //             }
                //             $absen->keterangan = $keterangan;
                //             $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                //             $absen->save();
                //         }
                //     }
                // }
                for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                    $tgl = \DateTime::createFromFormat('U', $tanggal);
                    $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                    if (!$this->checkAbsenPegawai($nip, $tgl->format('Y-m-d'))) {
                        try {
                            if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                                if (!$this->inputAbsenPegawai($kode_terminal, $no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'), $ym)) {
                                    $absen = new AbsenPegawai;
                                    $absen->id = 0;
                                    $absen->NIP = $nip;
                                    $absen->tanggal = $tgl->format('Y-m-d');
                                    $absen->status_masuk = 'TA';
                                    $absen->status_siang = 'TA';
                                    $absen->status_keluar = 'TA';
                                    $absen->status = 'TH';
                                    if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                                        $keterangan = $check->data->keterangan;
                                    }
                                    else {
                                        $keterangan = 'Tanpa Keterangan';
                                    }
                                    $absen->keterangan = $keterangan;
                                    $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                                    $absen->save();
                                }
                            }
                        }
                        catch (yii\db\IntegrityException $e) {
                            //
                        }
                    }
                }
            // }
            // else {
            //     for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
            //         $tgl = \DateTime::createFromFormat('U', $tanggal);
            //         $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

            //         if (!$this->checkAbsenPegawai($nip, $tgl->format('Y-m-d'))) {
            //             if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
            //                 if (!$this->inputAbsenPegawai($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
            //                     $absen = new AbsenPegawai;
            //                     $absen->id = 0;
            //                     $absen->NIP = $nip;
            //                     $absen->tanggal = $tgl->format('Y-m-d');
            //                     $absen->status_masuk = 'TA';
            //                     $absen->status_siang = 'TA';
            //                     $absen->status_keluar = 'TA';
            //                     $absen->status = 'TH';
            //                     if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
            //                         $keterangan = $check->data->keterangan;
            //                     }
            //                     else {
            //                         $keterangan = 'Tanpa Keterangan';
            //                     }
            //                     $absen->keterangan = $keterangan;
            //                     $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
            //                     $absen->save();
            //                 }
            //             }
            //         }
            //     }
            // }
        }
    }



    public function inputAbsenPegawai($kode_terminal, $no_absen, $nip, $tanggal, $hari, $ym) {
        // date_default_timezone_set('UTC');

        // $max_jam_masuk = '08:05:00';
        $max_jam_masuk = new \DateTime(MAX_MASUK, new \DateTimeZone(TIMEZONE));

        if ($hari == 'Fri') {
            // $min_jam_keluar = '16:30:00';
            $min_jam_keluar = new \DateTime(MIN_KELUAR_FRIDAY, new \DateTimeZone(TIMEZONE));
        }
        else {
            // $min_jam_keluar = '16:00:00';
            $min_jam_keluar = new \DateTime(MIN_KELUAR, new \DateTimeZone(TIMEZONE));
        }

        $minmax = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, MIN(DATE_FORMAT(date_time, "%H:%i:%s")) as min_time, MAX(DATE_FORMAT(date_time, "%H:%i:%s")) as max_time'])
            ->groupBy(['date'])
            ->where(['pin' => $no_absen])
            ->andWhere(['data_absen.kode_terminal' => $kode_terminal])
            ->andWhere(['DATE_FORMAT(date_time, "%Y-%m")'=>$ym])
            ->innerJoinWith(['pegawai'])
            ->all();

        $mid = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, DATE_FORMAT(date_time, "%H:%i:%s") as mid_time'])
            ->groupBy(['date'])
            ->where(['pin' => $no_absen])
            ->andWhere(['data_absen.kode_terminal' => $kode_terminal])
            ->andWhere(['DATE_FORMAT(date_time, "%Y-%m")'=>$ym])
            ->andWhere(['>', 'DATE_FORMAT(date_time, "%H:%i:%s")', '12:00:00'])
            // ->andWhere(['<', 'DATE_FORMAT(date_time, "%H:%i:%s")', '13:00:00'])
            ->innerJoinWith(['pegawai'])
            ->all();

        $md = ArrayHelper::map($mid, 'date', 'mid_time');
        $mn = ArrayHelper::map($minmax, 'date', 'min_time');
        $mx = ArrayHelper::map($minmax, 'date', 'max_time');
        $absen = array_merge_recursive($mn, $md, $mx);

        foreach ($absen as $date => $data) {
            if (count($data) == 2) $data[2] = $data[1];

            // $masuk = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? (new \DateTime($data[0], new \DateTimeZone(TIMEZONE)))->format('H:i:s') : '') : (new \DateTime($data[0], new \DateTimeZone(TIMEZONE)))->format('H:i:s'));

            // $siang = (($data[0] == $data[1] || $data[1] == $data[2]) && $data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? (new \DateTime($data[1], new \DateTimeZone(TIMEZONE)))->format('H:i:s') : '';

            // $keluar = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '' : (new \DateTime($data[2], new \DateTimeZone(TIMEZONE)))->format('H:i:s')) : (new \DateTime($data[2], new \DateTimeZone(TIMEZONE)))->format('H:i:s'));

            $masuk_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? $data[0] : '00:00:00') : $data[0]);
            $masuk = new \DateTime($masuk_, new \DateTimeZone(TIMEZONE));

            // $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) && $data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00';
            // $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00') : $data[1]);
            $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00') : (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00') );
            $siang = new \DateTime($siang_, new \DateTimeZone(TIMEZONE));

            $keluar_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '00:00:00' : $data[2]) : $data[2]);
            $keluar = new \DateTime($keluar_, new \DateTimeZone(TIMEZONE));

            // lihat data upacara
            if ($this->checkUpacara($nip, $tanggal)) {
                // $masuk = '08:00:00';
                $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
            }

            // lihat data absen manual
            // if ($manual = $this->checkAbsenManual($nip, $tanggal)) {
            //     if ($manual->absen == 1)
            //         $masuk = $manual->jam;
            //     elseif ($manual->absen == 2)
            //         $siang = $manual->jam;
            //     else
            //         $keluar = $manual->jam;
            // }

            // lihat data tugas luar
            if ($tl = $this->checkTugasLuar($nip, $tanggal)) {
                // if ($tl->dari_jam <= '08:05:00') {
                //     $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
                // }

                // if (($tl->dari_jam >= '12:00:00' && $tl->dari_jam < '13:00:00') || ($tl->sampai_jam >= '13:00:00')) {
                //     $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                // }

                // if ($tl->sampai_jam >= '16:00:00') {
                //     $keluar = new \DateTime('16:00:00', new \DateTimeZone(TIMEZONE));
                // }

                if ($tl->dari_jam <= '08:05:00') {
                    if ($tl->sampai_jam < '12:00:00') {
                        $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
                    }
                    elseif ($tl->sampai_jam >= '12:00:00' && $tl->sampai_jam < '16:00:00') {
                        $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                    }
                    else {
                        $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                        $keluar = new \DateTime('16:00:00', new \DateTimeZone(TIMEZONE));
                    }
                }
                elseif ($tl->dari_jam < '12:00:00') {
                    if ($tl->sampai_jam < '12:00:00') {
                        //
                    }
                    elseif ($tl->sampai_jam < '16:00:00') {
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                    }
                    else {
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                        $keluar = new \DateTime('16:00:00', new \DateTimeZone(TIMEZONE));
                    }
                }
                elseif ($tl->dari_jam < '13:00:00') {
                    if ($tl->sampai_jam < '16:00:00') {
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                    }
                    else {
                        $siang = new \DateTime('12:00:00', new \DateTimeZone(TIMEZONE));
                        $keluar = new \DateTime('16:00:00', new \DateTimeZone(TIMEZONE));
                    }
                }
                else {
                    if ($tl->sampai_jam < '16:00:00') {
                        //
                    }
                    else {
                        $keluar = new \DateTime('16:00:00', new \DateTimeZone(TIMEZONE));
                    }
                }
            }


            // $selisih_waktu_masuk = strtotime($masuk) - strtotime($max_jam_masuk);
            // $selisih_waktu_masuk = $masuk->format('U') - $max_jam_masuk->format('U');
            $selisih_waktu_masuk = $masuk->getTimestamp() - $max_jam_masuk->getTimestamp();

            if ($keluar->format('H:i:s') != '00:00:00')
                $selisih_waktu_keluar = $min_jam_keluar->getTimestamp() - $keluar->getTimestamp();
            else
                $selisih_waktu_keluar = 0;

            // hitung selisih waktu masuk
            if ($selisih_waktu_masuk > 0)
                $selisih_waktu_masuk = $selisih_waktu_masuk;
            else
                $selisih_waktu_masuk = 0;

            // hitung selisih waktu keluar
            if ($selisih_waktu_keluar > 0)
                $selisih_waktu_keluar = $selisih_waktu_keluar;
            else
                $selisih_waktu_keluar = 0;

            // status masuk
            if ($selisih_waktu_masuk > 0)
                $status_masuk = 'DT';
            if ($selisih_waktu_masuk == 0)
                $status_masuk = 'TW';
            if ($masuk->format('H:i:s') == '00:00:00')
                $status_masuk = 'TA';

            // status absen siang
            if ($siang->format('H:i:s') == '00:00:00')
                $status_siang = 'TA';
            else
                $status_siang = 'A';

            // status keluar
            if ($selisih_waktu_keluar > 0)
                $status_keluar = 'PC';
            if ($selisih_waktu_keluar == 0)
                $status_keluar = 'TW';
            if ($keluar->format('H:i:s') == '00:00:00')
                $status_keluar = 'TA';

            // $selisih_masuk = date("H:i:s", $selisih_waktu_masuk);
            $selisih_masuk = \DateTime::createFromFormat('U', $selisih_waktu_masuk);
            // $selisih_masuk->setTimezone(new \DateTimeZone(TIMEZONE));
            $s_masuk = ($selisih_waktu_masuk > 1) ? $selisih_masuk->format('H:i:s') : '';
            // echo $selisih_masuk->format('H:i:sP T').'<br>';

            // $selisih_keluar = date("H:i:s", $selisih_waktu_keluar);
            $selisih_keluar = \DateTime::createFromFormat('U', $selisih_waktu_keluar);
            // $selisih_keluar->setTimezone(new \DateTimeZone(TIMEZONE));
            $s_keluar = ($selisih_waktu_keluar > 1) ? $selisih_keluar->format('H:i:s') : '';

            if ($date == $tanggal) {
                // date_default_timezone_set('Asia/Jakarta');

                $absen = new AbsenPegawai;
                $absen->id = 0;
                $absen->NIP = $nip;
                $absen->tanggal = $tanggal;
                $absen->jam_masuk = ($masuk->format('H:i:s') != '00:00:00') ? $masuk->format('H:i:s') : '';
                $absen->jam_siang = ($siang->format('H:i:s') != '00:00:00') ? $siang->format('H:i:s') : '';
                $absen->jam_keluar = ($keluar->format('H:i:s') != '00:00:00') ? $keluar->format('H:i:s') : '';
                $absen->status_masuk = $status_masuk;
                $absen->status_siang = $status_siang;
                $absen->status_keluar = $status_keluar;
                $absen->selisih_jam_masuk = $s_masuk;
                $absen->selisih_jam_keluar = $s_keluar;
                $absen->status = ($absen->jam_masuk!='' && $absen->jam_siang!='' && $absen->jam_keluar!='') ? 'HD' : 'TH';
                if ($this->checkDataTugasLuar($nip, $tanggal)) {
                    $absen->keterangan = 'Tugas Luar';
                }
                $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                return $absen->save();
            }
        }
    }



    public function actionKehadiranPegawaiPerUnitKerja($unit)
    {
        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('kehadiran-pegawai-per-unit-kerja', [
            'model'=>$model,
            'unit'=>$unit,
        ]);
    }



    public function actionLihatKehadiran($nip)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->orderBy('tanggal')
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        return $this->render('lihat-kehadiran', [
            'model'=>$model,
            'data'=>$data,
            'bulan'=>$ym,
        ]);
    }







    public function checkLibur($tanggal) {
        $libur = HariLibur::find()->where(['DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$tanggal])->exists();
        return $libur;
    }

    public function checkAbsenPegawai($nip, $ymd) {
        $data = AbsenPegawai::find()
            ->where(['NIP'=>$nip])
            ->andWhere(['DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])
            ->exists();
        return $data;
    }

    public function checkDataKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
        return $data;
    }
 
    public function checkKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
        return $data;
    }

    public function checkDataUpacara($nip, $ymd) {
        $data = Upacara::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd, 'status'=>1])->exists();
        return $data;
    }

    public function checkUpacara($nip, $ymd) {
        $data = Upacara::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd, 'status'=>1])->one();
        return $data;
    }

    public function checkDataTugasLuar($nip, $ymd) {
        $data = TugasLuar::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
        return $data;
    }

    public function checkTugasLuar($nip, $ymd) {
        $data = TugasLuar::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
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

    public function checkDataAbsen($ym, $nip) {
        $data = AbsenPegawai::find()->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])->exists();
        return $data;
    }

    public function checkDataAbsenManual($nip, $ymd) {
        $data = DataAbsenManual::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
        return $data;
    }

    public function checkAbsenManual($nip, $ymd) {
        $data = DataAbsenManual::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->all();
        return $data;
    }

    public function getNama($nip) {
        $data = DataPegawai::find()->select('nama')->where(['NIP'=>$nip])->one();
        return $data->nama;
    }






    protected function findModelAbsenPegawai($id)
    {
        if (($model = AbsenPegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function findModelKeterangan($id)
    {
        if (($model = Keterangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function findModelTugasLuar($id)
    {
        if (($model = TugasLuar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    

    


























































    public function actionLihatKehadiranClone($nip)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = AbsenPegawaiClone::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->orderBy('tanggal')
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        return $this->render('lihat-kehadiran-clone', [
            'data'=>$data,
            'model'=>$model,
            'bulan'=>$ym,
        ]);
    }


    public function actionDataAbsen()
    {
        $model = DataAbsen::find()
            ->where(['PIN'=>3]);
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $page = 1;

        if (isset($_GET["page"]))
            $page = $_GET["page"];

        $limit = 100;
        $offset = $limit * ($page - 1);
        $pages->setPageSize($limit);

        $model = $model->offset($offset)
            ->limit($limit)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('data-absen', [
            'model'=>$model,
            'pages' => $pages,
        ]);

    }




    public function checkAbsenPegawaiClone($nip, $ymd) {
        $data = AbsenPegawaiClone::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
        return $data;
    }


    public function actionProsesKehadiranNip($nip)
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-2 month', $bulan);
        // $ym = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');
        // echo $ym.'x<br>';

        $model = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiranClone($data->NIP, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai. '.Yii::getLogger()->getElapsedTime());

        return $this->redirect(['kehadiran-per-unit-kerja']);
    }


    public function actionProsesKehadiranClone($nip, $no_absen, $ym)
    {
        // $month = date('m', strtotime($ym));
        // $year = date('Y', strtotime($ym));

        $ym_ = \DateTime::createFromFormat('Y-m', $ym);
        $month = $ym_->format('m');
        $year = $ym_->format('Y');

        $results = AbsenPegawaiClone::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        // $start_date = "01-".$month."-".$year;
        // $start_time = strtotime($start_date);
        // $end_time = strtotime("+1 month", $start_time);

        $start_date = $ym_->format('Y-m-01');
        $start_time = new \DateTime($start_date, new \DateTimeZone(TIMEZONE));
        $start = $start_time->format('U');

        $end_time = $start_time->add(new \DateInterval('P1M'));
        $end = $end_time->format('U');

        // echo $start_time->format('Y-m-d').'<br>';
        // echo $end_time->format('Y-m-d').'<br>';

        $date = new \DateTime(null, new \DateTimeZone(TIMEZONE));
        $todays_month = $date->format('m');

        if ($month == $todays_month) {
            // $end_time = strtotime(date('d').'-'.$month."-".$year);
            $end = $date->format('U');

            for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                $tgl = \DateTime::createFromFormat('U', $tanggal);
                $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                // if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                    // if (!$this->checkAbsenPegawai($nip, date('Y-m-d', $tanggal))) {
                    if (!$this->checkAbsenPegawaiClone($nip, $tgl->format('Y-m-d'))) {
                        // if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                        if (!$this->inputAbsenPegawaiClone($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
                            $absen = new AbsenPegawaiClone;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = $tgl->format('Y-m-d');
                            $absen->status = 'TH';
                            if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                                $keterangan = $check->data->keterangan;
                            }
                            else {
                                $keterangan = 'Tanpa Keterangan';
                            }
                            $absen->keterangan = $keterangan;
                            $absen->save();
                        }
                    }
                }
            }
        }
        else {
            if (count($results) == 0) {
                for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                    $tgl = \DateTime::createFromFormat('U', $tanggal);
                    $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                    // if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                    if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                        // if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                        if (!$this->inputAbsenPegawaiClone($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
                            $absen = new AbsenPegawaiClone;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = $tgl->format('Y-m-d');
                            $absen->status_masuk = 'TA';
                            $absen->status_siang = 'TA';
                            $absen->status_keluar = 'TA';
                            $absen->status = 'TH';
                            if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                                $keterangan = $check->data->keterangan;
                            }
                            else {
                                $keterangan = 'Tanpa Keterangan';
                            }
                            $absen->keterangan = $keterangan;
                            $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                            $absen->save();
                        }
                    }
                }
            }
            else {
                for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                    $tgl = \DateTime::createFromFormat('U', $tanggal);
                    $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                    if (!$this->checkAbsenPegawaiClone($nip, $tgl->format('Y-m-d'))) {
                        if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                            if (!$this->inputAbsenPegawaiClone($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
                                $absen = new AbsenPegawaiClone;
                                $absen->id = 0;
                                $absen->NIP = $nip;
                                $absen->tanggal = $tgl->format('Y-m-d');
                                $absen->status_masuk = 'TA';
                                $absen->status_siang = 'TA';
                                $absen->status_keluar = 'TA';
                                $absen->status = 'TH';
                                if ($check = $this->checkKeterangan($nip, $tgl->format('Y-m-d'))) {
                                    $keterangan = $check->data->keterangan;
                                }
                                else {
                                    $keterangan = 'Tanpa Keterangan';
                                }
                                $absen->keterangan = $keterangan;
                                $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                                $absen->save();
                            }
                        }
                    }
                }
            }
        }
    }


    public function inputAbsenPegawaiClone($no_absen, $nip, $tanggal, $hari) {
        // date_default_timezone_set('UTC');

        // $max_jam_masuk = '08:05:00';
        $max_jam_masuk = new \DateTime(MAX_MASUK, new \DateTimeZone(TIMEZONE));

        if ($hari == 'Fri') {
            // $min_jam_keluar = '16:30:00';
            $min_jam_keluar = new \DateTime(MIN_KELUAR_FRIDAY, new \DateTimeZone(TIMEZONE));
        }
        else {
            // $min_jam_keluar = '16:00:00';
            $min_jam_keluar = new \DateTime(MIN_KELUAR, new \DateTimeZone(TIMEZONE));
        }

        $mid = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, DATE_FORMAT(date_time, "%H:%i:%s") as mid_time'])
            ->groupBy(['date'])
            ->where(['pin' => $no_absen])
            ->andWhere(['>', 'DATE_FORMAT(date_time, "%H:%i:%s")', '12:00:00'])
            // ->andWhere(['<', 'DATE_FORMAT(date_time, "%H:%i:%s")', '13:00:00'])
            ->innerJoinWith(['pegawai'])
            ->all();

        $minmax = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, MIN(DATE_FORMAT(date_time, "%H:%i:%s")) as min_time, MAX(DATE_FORMAT(date_time, "%H:%i:%s")) as max_time'])
            ->groupBy(['date'])
            ->where(['pin' => $no_absen])
            ->innerJoinWith(['pegawai'])
            ->all();

        $md = ArrayHelper::map($mid, 'date', 'mid_time');
        $mn = ArrayHelper::map($minmax, 'date', 'min_time');
        $mx = ArrayHelper::map($minmax, 'date', 'max_time');
        $absen = array_merge_recursive($mn, $md, $mx);

        foreach ($absen as $date => $data) {
            if (count($data) == 2) $data[2] = $data[1];

            // $masuk = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? (new \DateTime($data[0], new \DateTimeZone(TIMEZONE)))->format('H:i:s') : '') : (new \DateTime($data[0], new \DateTimeZone(TIMEZONE)))->format('H:i:s'));

            // $siang = (($data[0] == $data[1] || $data[1] == $data[2]) && $data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? (new \DateTime($data[1], new \DateTimeZone(TIMEZONE)))->format('H:i:s') : '';

            // $keluar = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '' : (new \DateTime($data[2], new \DateTimeZone(TIMEZONE)))->format('H:i:s')) : (new \DateTime($data[2], new \DateTimeZone(TIMEZONE)))->format('H:i:s'));

            $masuk_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? $data[0] : '00:00:00') : $data[0]);
            $masuk = new \DateTime($masuk_, new \DateTimeZone(TIMEZONE));

            // $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) && $data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00';
            $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00') : $data[1]);
            $siang = new \DateTime($siang_, new \DateTimeZone(TIMEZONE));

            $keluar_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '00:00:00' : $data[2]) : $data[2]);
            $keluar = new \DateTime($keluar_, new \DateTimeZone(TIMEZONE));

            // lihat data upacara
            if ($this->checkUpacara($nip, $tanggal)) {
                // $masuk = '08:00:00';
                $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
            }


            // $selisih_waktu_masuk = strtotime($masuk) - strtotime($max_jam_masuk);
            // $selisih_waktu_masuk = $masuk->format('U') - $max_jam_masuk->format('U');
            $selisih_waktu_masuk = $masuk->getTimestamp() - $max_jam_masuk->getTimestamp();

            if ($keluar->format('H:i:s') != '00:00:00')
                $selisih_waktu_keluar = $min_jam_keluar->getTimestamp() - $keluar->getTimestamp();
            else
                $selisih_waktu_keluar = 0;

            // hitung selisih waktu masuk
            if ($selisih_waktu_masuk > 0)
                $selisih_waktu_masuk = $selisih_waktu_masuk;
            else
                $selisih_waktu_masuk = 0;

            // hitung selisih waktu keluar
            if ($selisih_waktu_keluar > 0)
                $selisih_waktu_keluar = $selisih_waktu_keluar;
            else
                $selisih_waktu_keluar = 0;

            // status masuk
            if ($selisih_waktu_masuk > 0)
                $status_masuk = 'DT';
            if ($selisih_waktu_masuk == 0)
                $status_masuk = 'TW';
            if ($masuk->format('H:i:s') == '00:00:00')
                $status_masuk = 'TA';

            // status absen siang
            if ($siang->format('H:i:s') == '00:00:00')
                $status_siang = 'TA';
            else
                $status_siang = 'A';

            // status keluar
            if ($selisih_waktu_keluar > 0)
                $status_keluar = 'PC';
            if ($selisih_waktu_keluar == 0)
                $status_keluar = 'TW';
            if ($keluar->format('H:i:s') == '00:00:00')
                $status_keluar = 'TA';

            // $selisih_masuk = date("H:i:s", $selisih_waktu_masuk);
            $selisih_masuk = \DateTime::createFromFormat('U', $selisih_waktu_masuk);
            // $selisih_masuk->setTimezone(new \DateTimeZone(TIMEZONE));
            $s_masuk = ($selisih_waktu_masuk > 1) ? $selisih_masuk->format('H:i:s') : '';
            // echo $selisih_masuk->format('H:i:sP T').'<br>';

            // $selisih_keluar = date("H:i:s", $selisih_waktu_keluar);
            $selisih_keluar = \DateTime::createFromFormat('U', $selisih_waktu_keluar);
            // $selisih_keluar->setTimezone(new \DateTimeZone(TIMEZONE));
            $s_keluar = ($selisih_waktu_keluar > 1) ? $selisih_keluar->format('H:i:s') : '';

            if ($date == $tanggal) {
                // date_default_timezone_set('Asia/Jakarta');

                $absen = new AbsenPegawaiClone;
                $absen->id = 0;
                $absen->NIP = $nip;
                $absen->tanggal = $tanggal;
                $absen->jam_masuk = ($masuk->format('H:i:s') != '00:00:00') ? $masuk->format('H:i:s') : '';
                $absen->jam_siang = ($siang->format('H:i:s') != '00:00:00') ? $siang->format('H:i:s') : '';
                $absen->jam_keluar = ($keluar->format('H:i:s') != '00:00:00') ? $keluar->format('H:i:s') : '';
                $absen->status_masuk = $status_masuk;
                $absen->status_siang = $status_siang;
                $absen->status_keluar = $status_keluar;
                $absen->selisih_jam_masuk = $s_masuk;
                $absen->selisih_jam_keluar = $s_keluar;
                $absen->status = ($absen->jam_masuk!='' && $absen->jam_siang!='' && $absen->jam_keluar!='') ? 'HD' : 'TH';
                $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                return $absen->save();
            }
        }
    }




    


    



    
    public function actionProsesKehadiranAll()
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-1 month', $bulan);
        // $ym = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $ym = $bulan->format('Y-m');

        $model = DataPegawai::find()
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiran($data->NIP, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai.');

        return $this->redirect(['kehadiran-per-unit-kerja']);
    }





    public function inputAbsenPegawaiPrev($id, $nip, $tanggal, $hari) {
        date_default_timezone_set('UTC');

        $max_jam_masuk = '07:45:00';

        if ($hari == 'Fri')
            $min_jam_keluar = '16:45:00';
        else
            $min_jam_keluar = '16:15:00';

        $absen = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, MIN(DATE_FORMAT(date_time, "%H:%i:%s")) as min_time, MAX(DATE_FORMAT(date_time, "%H:%i:%s")) as max_time'])
            ->groupBy(['date'])
            ->where(['pin' => $id])
            ->innerJoinWith(['pegawai'])
            ->all();

        foreach ($absen as $data) {
            $masuk = (($data->min_time == $data->max_time) ? (($data->min_time < '12:00:00') ? $data->min_time : '') : $data->min_time);
            $keluar =  (($data->max_time == $data->min_time) ? (($data->max_time < '12:00:00') ? '' : $data->max_time) : $data->max_time);

            $selisih_waktu_masuk = strtotime($masuk) - strtotime($max_jam_masuk);
            $selisih_waktu_keluar = strtotime($min_jam_keluar) - strtotime($keluar);

            // hitung selisih waktu masuk
            if ($selisih_waktu_masuk > '0')
                $selisih_waktu_masuk = $selisih_waktu_masuk;
            else
                $selisih_waktu_masuk = '0';

            // hitung selisih waktu keluar
            if ($selisih_waktu_keluar > '0')
                $selisih_waktu_keluar = $selisih_waktu_keluar;
            else
                $selisih_waktu_keluar = '0';

            // status masuk
            if ($selisih_waktu_masuk > '0')
                $status_masuk = 'DT';
            if ($selisih_waktu_masuk == '0')
                $status_masuk = 'TW';
            if ($masuk == '')
                $status_masuk = 'TA';

            // status keluar
            if ($selisih_waktu_keluar > '0')
                $status_keluar = 'PC';
            if ($selisih_waktu_keluar == '0')
                $status_keluar = 'TW';
            if ($keluar == '')
                $status_keluar = 'TA';

            $selisih_masuk = date("H:i:s", $selisih_waktu_masuk);
            $s_masuk = ($selisih_waktu_masuk > '1') ? $selisih_masuk : '';

            $selisih_keluar = date("H:i:s", $selisih_waktu_keluar);
            $s_keluar = ($selisih_waktu_keluar > '1') ? $selisih_keluar : '';

            if ($data->date == $tanggal) {
                date_default_timezone_set('Asia/Jakarta');

                $absen = new AbsenPegawai;
                $absen->id = 0;
                $absen->NIP = $nip;
                $absen->tanggal = $tanggal;
                $absen->jam_masuk = $masuk;
                $absen->jam_keluar = $keluar;
                $absen->status_masuk = $status_masuk;
                $absen->status_keluar = $status_keluar;
                $absen->selisih_jam_masuk = $s_masuk;
                $absen->selisih_jam_keluar = $s_keluar;
                $absen->status = ($absen->jam_masuk!='' && $absen->jam_keluar!='') ? 'HD' : 'TH';
                $absen->last_updated = date('Y-m-d H:i:s');
                return $absen->save();
            }
        }
    }

    public function actionAbsen($id) {
        $mid = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, DATE_FORMAT(date_time, "%H:%i:%s") as mid_time'])
            ->groupBy(['date'])
            ->where(['pin' => $id])
            ->andWhere(['>', 'DATE_FORMAT(date_time, "%H:%i:%s")', '12:00:00'])
            // ->andWhere(['<', 'DATE_FORMAT(date_time, "%H:%i:%s")', '13:00:00'])
            ->innerJoinWith(['pegawai'])
            ->all();

        $absen = DataAbsen::find()
            ->select(['DATE_FORMAT(date_time, "%Y-%m-%d") as date, MIN(DATE_FORMAT(date_time, "%H:%i:%s")) as min_time, MAX(DATE_FORMAT(date_time, "%H:%i:%s")) as max_time'])
            ->groupBy(['date'])
            ->where(['pin' => $id])
            ->innerJoinWith(['pegawai'])
            ->all();
        
        $ar1 = ArrayHelper::map($mid, 'date', 'mid_time');
        $ar2 = ArrayHelper::map($absen, 'date', 'min_time');
        $ar3 = ArrayHelper::map($absen, 'date', 'max_time');
        $ar = array_merge_recursive($ar2, $ar1, $ar3);

        echo "<pre>";
        echo print_r($ar);
        echo "</pre>";

        foreach ($ar as $data) {
            echo 'min: '.$data[0].'<br>';
            echo 'mid: '.$data[1].'<br>';
            echo 'max: '.$data[2].'<br><br>';
        }
    }


    


    public function actionKehadiran($nip, $id)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
        }
        else {
            $month = date('m');
            $year = date('Y');
        }

        $ym = $year.'-'.$month;

        $results = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 month", $start_time);

        if ($month == date('m')) {
            $end_time = strtotime(date('d').'-'.$month."-".$year);
            for ($i=$start_time; $i<$end_time; $i+=86400) {
                if (!$this->checkLibur(date('Y-m-d', $i)) && date('D', $i) != 'Sat' && date('D', $i) != 'Sun') {
                    if (!$this->checkDataAbsenPegawai($nip, date('Y-m-d', $i))) {
                        if (!$this->inputAbsenPegawai($id, $nip, date('Y-m-d', $i), date('D', $i))) {
                            $absen = new AbsenPegawai;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = date('Y-m-d', $i);
                            $absen->status = 'TH';
                            $absen->save();
                        }
                    }
                }
            }
        }
        else {
            if (count($results) == 0) {
                for ($i=$start_time; $i<$end_time; $i+=86400) {
                    if (!$this->checkLibur(date('Y-m-d', $i)) && date('D', $i) != 'Sat' && date('D', $i) != 'Sun') {
                        if (!$this->inputAbsenPegawai($id, $nip, date('Y-m-d', $i), date('D', $i))) {
                            $absen = new AbsenPegawai;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = date('Y-m-d', $i);
                            $absen->status = 'TH';
                            $absen->save();
                        }
                    }
                }
            }
        }

        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->all();

        return $this->render('kehadiran', [
            'model'=>$model,
        ]);
    }



    


    public function actionEditKehadiran($id)
    {
        $model = $this->findModelAbsenPegawai($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['lihat-kehadiran', 'nip'=>$model->nip]);
        } else {
            return $this->render('edit-kehadiran', [
                'model' => $model,
            ]);
        }
    }



}
