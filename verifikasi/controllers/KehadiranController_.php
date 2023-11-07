<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\UnitKerja;
use common\models\HariLibur;
use common\models\Keterangan;
use common\models\Upacara;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

// $formatter = new NumberFormatter('en_US', NumberFormatter::PERCENT);

date_default_timezone_set('UTC');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

/**
 * KehadiranController implements the CRUD actions for AbsenPegawai model.
 */
class KehadiranController extends Controller
{
    public $layout = 'admin';
    //protected $nip;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    // public function getBuildPercentage() {
    //     $currentTime = strtotime(date('Y-m-d H:i:s')); //50
    //     $boughtTime = strtotime($this->boughtTime); 
    //     $finishTime = strtotime($this->finishTime); //100

    //     $first = $currentTime - $boughtTime; 
    //     $second = $finishTime - $boughtTime; 

    //     $percentage =   round(($first / $second) * 100);    

    //     // if the percentage is higher than 100 -> item is finished
    //     // if($percentage >= 100)
    //     //     $this->setToFinished($this->id); 

    //     return $percentage; 
    // }

    // public function actionPercentage($id) {
    //     if (Yii::app()->request->isAjaxRequest) {
    //        $item = YourModelName::model()->findByPk($id); //obtain instance of object containing your function
    //        echo $item->getBuildPercentage(); //to return value in ajax, simply echo it   
    //     }
    // }

    // Yii::app()->clientScript->registerCoreScript('jquery');
    // Yii::app()->clientScript->registerScript('ajax-percentage','
    //    var interval = 1000;
    //    setInterval(function() { $.ajax(
    //         type: "GET",
    //         url: '.Yii::app()->createUrl('yourController/percentage', array('id'=>$item->id)).',
    //         success: function (percents) {
    //             // you have got your percents, so you can now assign it to progressbar value here
    //         }
    //  )}, interval);
    // ');


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

        // $model = Upacara::find()
        //   ->where(['unit_kerja'=>$unit, 'tanggal'=>$ymd])
        //   ->innerJoinWith(['nip'])
        //   ->all();

        $upacara = [];

        foreach ($model as $model) {
            $upacara[$model->NIP] = new Upacara();
        }

        if (Model::loadMultiple($upacara, Yii::$app->request->post()) && Model::validateMultiple($upacara)) {
            foreach ($upacara as $upacara) {
                $upacara->save(false);
            }
            return $this->redirect(['upacara']);
        }

        return $this->render('upacara-pegawai', [
            'tanggal' => $ymd,
            'upacara' => $upacara,
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        if (Yii::$app->request->post('dari') == '' || Yii::$app->request->post('nip') == '' || Yii::$app->request->post('keterangan') == '') {
            return $this->redirect(['ketidakhadiran', 'status' => 404]);
        }
        else {
            $start_time = strtotime(Yii::$app->request->post('dari'));
            if (Yii::$app->request->post('sampai') == '')
                $end_time = strtotime(Yii::$app->request->post('dari'));
            else
                $end_time = strtotime(Yii::$app->request->post('sampai'));

            for ($tanggal=$start_time; $tanggal<=$end_time; $tanggal+=86400) {
                if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                    $ket = new Keterangan;
                    $ket->id = 0;
                    $ket->NIP = Yii::$app->request->post('nip');
                    $ket->no_sk = Yii::$app->request->post('no_sk');
                    $ket->tanggal = date('Y-m-d', $tanggal);
                    $ket->keterangan = Yii::$app->request->post('keterangan');
                    $ket->save();
                }
            }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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


    public function actionKehadiranPerUnitKerja()
    {
        $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        $bulan = strtotime('-1 month', $bulan);
        $periode = date('Y-m', $bulan);

        $model = UnitKerja::find()->all();

        return $this->render('kehadiran-per-unit-kerja', [
            'model'=>$model,
            'bulan'=>$bulan,
            'periode'=>$periode,
        ]);
    }


    public function actionProsesKehadiranAll()
    {
        $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        $bulan = strtotime('-1 month', $bulan);
        $ym = date('Y-m', $bulan);

        $model = DataPegawai::find()
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiran($data->NIP, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai.');

        return $this->redirect(['kehadiran-per-unit-kerja']);
    }


    public function actionProsesKehadiranPerUnitKerja($unit)
    {
        $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        $bulan = strtotime('-1 month', $bulan);
        $ym = date('Y-m', $bulan);

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiran($data->NIP, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai.'.Yii::getLogger()->getElapsedTime());

        return $this->redirect(['kehadiran-per-unit-kerja', 'unit' => $unit]);
    }



    public function actionProsesKehadiran($nip, $no_absen, $ym)
    {
        $month = date('m', strtotime($ym));
        $year = date('Y', strtotime($ym));

        $results = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);
        $end_time = strtotime("+1 month", $start_time);

        if ($month == date('m')) {
            $end_time = strtotime(date('d').'-'.$month."-".$year);
            for ($tanggal=$start_time; $tanggal<$end_time; $tanggal+=86400) {
                if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                    if (!$this->checkAbsenPegawai($nip, date('Y-m-d', $tanggal))) {
                        if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                            $absen = new AbsenPegawai;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = date('Y-m-d', $tanggal);
                            $absen->status = 'TH';
                            if ($check = $this->checkKeterangan($nip, date('Y-m-d', $tanggal))) {
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
                for ($tanggal=$start_time; $tanggal<$end_time; $tanggal+=86400) {
                    if (!$this->checkLibur(date('Y-m-d', $tanggal)) && date('D', $tanggal) != 'Sat' && date('D', $tanggal) != 'Sun') {
                        if (!$this->inputAbsenPegawai($no_absen, $nip, date('Y-m-d', $tanggal), date('D', $tanggal))) {
                            $absen = new AbsenPegawai;
                            $absen->id = 0;
                            $absen->NIP = $nip;
                            $absen->tanggal = date('Y-m-d', $tanggal);
                            $absen->status_masuk = 'TA';
                            $absen->status_siang = 'TA';
                            $absen->status_keluar = 'TA';
                            $absen->status = 'TH';
                            if ($check = $this->checkKeterangan($nip, date('Y-m-d', $tanggal))) {
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
    }


    public function checkLibur($tanggal) {
        $libur = HariLibur::find()->where(['DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$tanggal])->exists();
        return $libur;
    }

    public function checkAbsenPegawai($nip, $ymd) {
        $data = AbsenPegawai::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
        return $data;
    }
 
    public function checkKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
        return $data;
    }

    public function checkUpacara($nip, $ymd) {
        $data = Upacara::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd, 'status'=>1])->one();
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


    public function inputAbsenPegawai($no_absen, $nip, $tanggal, $hari) {
        date_default_timezone_set('UTC');

        $max_jam_masuk = '08:05:00';

        if ($hari == 'Fri')
            $min_jam_keluar = '16:30:00';
        else
            $min_jam_keluar = '16:00:00';

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

        // echo '<pre>';
        // print_r($absen);
        // echo '</pre>';

        foreach ($absen as $date => $data) {
            if (count($data) == 2) $data[2] = $data[1];

            $masuk = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? $data[0] : '') : $data[0]);
            // $siang = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '') : $data[1]);
            $siang = (($data[0] == $data[1] || $data[1] == $data[2]) && $data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '';
            $keluar = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '' : $data[2]) : $data[2]);

            // lihat data upacara
            if ($this->checkUpacara($nip, $tanggal)) {
                $masuk = '08:00:00';
            }


            $selisih_waktu_masuk = strtotime($masuk) - strtotime($max_jam_masuk);

            if ($keluar != '')
                $selisih_waktu_keluar = strtotime($min_jam_keluar) - strtotime($keluar);
            else
                $selisih_waktu_keluar = '';

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

            // status absen siang
            if ($siang == '')
                $status_siang = 'TA';
            else
                $status_siang = 'A';

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

            if ($date == $tanggal) {
                // date_default_timezone_set('Asia/Jakarta');

                $absen = new AbsenPegawai;
                $absen->id = 0;
                $absen->NIP = $nip;
                $absen->tanggal = $tanggal;
                $absen->jam_masuk = $masuk;
                $absen->jam_siang = $siang;
                $absen->jam_keluar = $keluar;
                $absen->status_masuk = $status_masuk;
                $absen->status_siang = $status_siang;
                $absen->status_keluar = $status_keluar;
                $absen->selisih_jam_masuk = $s_masuk;
                $absen->selisih_jam_keluar = $s_keluar;
                $absen->status = ($absen->jam_masuk!='' && $absen->jam_siang!='' && $absen->jam_keluar!='') ? 'HD' : 'TH';
                $absen->last_updated = date('Y-m-d H:i:s');
                return $absen->save();
            }
        }
    }



    public function actionLihatKehadiran($nip)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            $bulan = strtotime('-1 month', $bulan);
            $ym = date('Y-m', $bulan);
            // $month = date('m');
            // $year = date('Y');
        }

        // $ym = $year.'-'.$month;

        // $results = AbsenPegawai::find()
        //     ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
        //     ->all();

        // $start_date = "01-".$month."-".$year;
        // $start_time = strtotime($start_date);
        // $end_time = strtotime("+1 month", $start_time);

        // if ($month == date('m')) {
        //     $end_time = strtotime(date('d').'-'.$month."-".$year);
        //     for ($i=$start_time; $i<$end_time; $i+=86400) {
        //         if (!$this->checkLibur(date('Y-m-d', $i)) && date('D', $i) != 'Sat' && date('D', $i) != 'Sun') {
        //             if (!$this->checkDataAbsenPegawai($nip, date('Y-m-d', $i))) {
        //                 if (!$this->inputAbsenPegawai($id, $nip, date('Y-m-d', $i), date('D', $i))) {
        //                     $absen = new AbsenPegawai;
        //                     $absen->id = 0;
        //                     $absen->NIP = $nip;
        //                     $absen->tanggal = date('Y-m-d', $i);
        //                     $absen->status = 'TH';
        //                     $absen->save();
        //                 }
        //             }
        //         }
        //     }
        // }
        // else {
        //     if (count($results) == 0) {
        //         for ($i=$start_time; $i<$end_time; $i+=86400) {
        //             if (!$this->checkLibur(date('Y-m-d', $i)) && date('D', $i) != 'Sat' && date('D', $i) != 'Sun') {
        //                 if (!$this->inputAbsenPegawai($id, $nip, date('Y-m-d', $i), date('D', $i))) {
        //                     $absen = new AbsenPegawai;
        //                     $absen->id = 0;
        //                     $absen->NIP = $nip;
        //                     $absen->tanggal = date('Y-m-d', $i);
        //                     $absen->status = 'TH';
        //                     $absen->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->all();

        return $this->render('lihat-kehadiran', [
            'data'=>$data,
            'model'=>$model,
            'bulan'=>$ym,
        ]);
    }


    public function actionKehadiranPegawaiPerUnitKerja($unit)
    {
        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        return $this->render('kehadiran-pegawai-per-unit-kerja', [
            'unit'=>$unit,
            'model'=>$model,
        ]);
    }




















    public function actionKehadiranPegawai()
    {
        $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        $bulan = strtotime('-1 month', $bulan);

        $model = DataPegawai::find()->all();

        return $this->render('kehadiran-pegawai', [
            'model'=>$model,
            'bulan'=>$bulan,
        ]);
    }


    public function checkDataAbsen($ym, $nip) {
        $data = AbsenPegawai::find()->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])->exists();
        return $data;
    }


    public function getNama($nip) {
        $data = DataPegawai::find()->select('nama')->where(['NIP'=>$nip])->one();
        return $data->nama;
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
}
