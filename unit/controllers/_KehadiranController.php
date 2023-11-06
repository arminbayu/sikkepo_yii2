<?php

namespace unit\controllers;

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

ini_set('max_execution_time', -1);
ini_set('memory_limit', '1024M');

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    public function getUnit() {
        return Yii::$app->user->identity->pegawai->unit_kerja;
    }



    public function actionUpacara()
    {
        $status = 0;

        return $this->render('upacara', [
            'status'=>$status,
        ]);
    }



    public function actionUpacaraPegawai($ymd)
    {
        if ($ymd == '')
            return $this->redirect(['upacara', 'status' => 404]);

        $model = DataPegawai::find()
          ->where(['unit_kerja'=>$this->unit, 'status'=>1])
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
        return $this->render('edit-upacara', [
            'unit' => $this->unit,
        ]);
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
        return $this->render('ketidakhadiran', [
            'unit' => $this->unit,
        ]);
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
        return $this->render('edit-ketidakhadiran', [
            'unit' => $this->unit,
        ]);
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



    public function actionKehadiranPerUnitKerja()
    {
        // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
        // $bulan = strtotime('-1 month', $bulan);
        // $periode = date('Y-m', $bulan);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P1M'));
        $periode = $bulan->format('Y-m');
        $bulan = $bulan->format('U');

        $model = UnitKerja::find()
            ->where(['kode'=>$this->unit])
            ->all();

        return $this->render('kehadiran-per-unit-kerja', [
            'model'=>$model,
            'periode'=>$periode,
        ]);
    }



    public function actionProsesKehadiranPerUnitKerja()
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
            ->andWhere(['data_pegawai.unit_kerja'=>$this->unit])
            ->innerJoinWith(['nip'])
            ->all();

        // proses hapus data absen pegawai
        AbsenPegawai::deleteAll(['IN', 'NIP', array_values($absen_pegawai)]);

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$this->unit, 'status'=>1])
            ->all();

        foreach ($model as $data) {
            $this->actionProsesKehadiran($data->NIP, $data->no_absen, $ym);
        }

        Yii::$app->session->setFlash('success', 'Proses selesai. '.Yii::getLogger()->getElapsedTime());

        return $this->redirect(['kehadiran-per-unit-kerja']);
    }



    public function actionProsesKehadiran($nip, $no_absen, $ym)
    {
        $ym_ = \DateTime::createFromFormat('Y-m', $ym);
        $month = $ym_->format('m');
        $year = $ym_->format('Y');

        $start_date = $ym_->format('Y-m-01');
        $start_time = new \DateTime($start_date, new \DateTimeZone(TIMEZONE));
        $start = $start_time->format('U');

        $end_time = $start_time->add(new \DateInterval('P1M'));
        $end = $end_time->format('U');

        $date = new \DateTime(null, new \DateTimeZone(TIMEZONE));
        $todays_month = $date->format('m');

        if ($month == $todays_month) {
            $end = $date->format('U');

            for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                $tgl = \DateTime::createFromFormat('U', $tanggal);
                $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                    if (!$this->checkAbsenPegawai($nip, $tgl->format('Y-m-d'))) {
                        if (!$this->inputAbsenPegawai($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
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
            for ($tanggal=$start; $tanggal<$end; $tanggal+=86400) {
                $tgl = \DateTime::createFromFormat('U', $tanggal);
                $tgl->setTimezone(new \DateTimeZone(TIMEZONE));

                if (!$this->checkAbsenPegawai($nip, $tgl->format('Y-m-d'))) {
                    if (!$this->checkLibur($tgl->format('Y-m-d')) && $tgl->format('D') != 'Sat' && $tgl->format('D') != 'Sun') {
                        if (!$this->inputAbsenPegawai($no_absen, $nip, $tgl->format('Y-m-d'), $tgl->format('D'))) {
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
            }
        }
    }



    public function inputAbsenPegawai($no_absen, $nip, $tanggal, $hari) {
        $max_jam_masuk = new \DateTime(MAX_MASUK, new \DateTimeZone(TIMEZONE));

        if ($hari == 'Fri') {
            $min_jam_keluar = new \DateTime(MIN_KELUAR_FRIDAY, new \DateTimeZone(TIMEZONE));
        }
        else {
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

            $masuk_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[0] >= '07:00:00' && $data[0] < '12:00:00') ? $data[0] : '00:00:00') : $data[0]);
            $masuk = new \DateTime($masuk_, new \DateTimeZone(TIMEZONE));

            $siang_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[1] >= '12:00:00' && $data[1] <= '13:00:00') ? $data[1] : '00:00:00') : $data[1]);
            $siang = new \DateTime($siang_, new \DateTimeZone(TIMEZONE));

            $keluar_ = (($data[0] == $data[1] || $data[1] == $data[2]) ? (($data[2] < '13:00:00') ? '00:00:00' : $data[2]) : $data[2]);
            $keluar = new \DateTime($keluar_, new \DateTimeZone(TIMEZONE));

            // lihat data upacara
            if ($this->checkUpacara($nip, $tanggal)) {
                $masuk = new \DateTime('08:00:00', new \DateTimeZone(TIMEZONE));
            }

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

            $selisih_masuk = \DateTime::createFromFormat('U', $selisih_waktu_masuk);
            $s_masuk = ($selisih_waktu_masuk > 1) ? $selisih_masuk->format('H:i:s') : '';

            $selisih_keluar = \DateTime::createFromFormat('U', $selisih_waktu_keluar);
            $s_keluar = ($selisih_waktu_keluar > 1) ? $selisih_keluar->format('H:i:s') : '';

            if ($date == $tanggal) {
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
                $absen->last_updated = (new \DateTime(null, new \DateTimeZone(TIMEZONE)))->format('Y-m-d H:i:s');
                return $absen->save();
            }
        }
    }



    public function actionKehadiranPegawaiPerUnitKerja()
    {
        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$this->unit, 'status'=>1])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$this->unit])
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
        $data = AbsenPegawai::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->exists();
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



}
