<?php
namespace apiv2\controllers;

use Yii;
use yii\rest\Controller;
use apiv2\models\User;
use apiv2\models\UserApi;
use common\models\Config;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\PrestasiPerilakuKerja;
use common\models\Keterangan;
use common\models\TugasLuar;
//use common\models\DataAbsenManualMobile;
use common\models\JadwalWFH;
use yii\web\UploadedFile;
use yii\filters\Cors;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use \Firebase\JWT\JWT;

/**
 * AbsenPegawaiController implements the CRUD actions for AbsenPegawai model.
 */
class SiteController extends Controller
{    

    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            // action that you don't want to authenticate such as login
            'except' => ['index', 'kehadiran', 'tpp', 'upload-photo', 'get-tugas-luar', 'tugas-luar', 'update-status', 'update-tugas', 'radius', 'absen', 'check-wfh', 'jadwal-wfh', 'absen-hari-ini', 'time-range', 'check-absen-tugas-luar', 'check-absen-masuk', 'check-absen-siang', 'check-absen-pulang', 'location-status']
        ];

        return $behaviors;

        // $behaviors = parent::behaviors();
        // $behaviors['authenticator'] = [
        //     //'class' => HttpBasicAuth::className(),     // use his for basic
        //     //'class' => HttpBearerAuth::className(),    // use his for bearer
        //     'class' => CompositeAuth::className(),       // use his for both of them
        //     'authMethods' => [
        //         HttpBasicAuth::className(),
        //         HttpBearerAuth::className(),
        //         QueryParamAuth::className(),
        //     ],
        // ];
        // return $behaviors;
    }


    public function actionIndex($token) {
        $user = UserApi::getUsername($token);

        return ['user' => $user,
                'unit' => $user->unitKerja->nama,
                'coords' => $user->unitKerja->koordinat,
        ];
    }


    public function actionTimeRange() {
        $model = Config::find()->one();

        return [
            'awal_m' => $model->awal_m,
            'akhir_m' => $model->akhir_m,
            'awal_s' => $model->awal_s,
            'akhir_s' => $model->akhir_s,
            'awal_p' => $model->awal_p,
            'akhir_p' => $model->akhir_p,
        ];
    }


    public function actionLocationStatus() {
        $model = Config::find()->one();

        return ['location_status' => $model->location_status];
    }


    public function actionCheckAbsenTugasLuar($id) {
        $model = TugasLuar::findOne(['id' => $id]);
        
        if ($model->status == 1)
            return ['status' => 1];

        return ['status' => 0];
    }


    public function actionCheckWfh($token, $tanggal) {
        $user = UserApi::getUsername($token);
        $model = JadwalWFH::findOne(['NIP' => $user, 'tanggal' => $tanggal]);
        
        if ($model)
            return ['status' => 1];
        
        return ['status' => 0];
    }  


    public function actionCheckAbsenMasuk($token, $tanggal, $absen) {
        $user = UserApi::getUsername($token);
        //$model = DataAbsenManualMobile::findOne(['NIP' => $user, 'tanggal' => $tanggal, 'absen' => $absen]);
        
        if ($model)
            return [
                'status' => 1,
                'origin' => $model->origin
            ];

        return ['status' => 0];
    }


    public function actionCheckAbsenSiang($token, $tanggal, $absen) {
        $user = UserApi::getUsername($token);
        //$model = DataAbsenManualMobile::findOne(['NIP' => $user, 'tanggal' => $tanggal, 'absen' => $absen]);
        
        if ($model)
            return [
                'status' => 1,
                'origin' => $model->origin
            ];

        return ['status' => 0];
    }


    public function actionCheckAbsenPulang($token, $tanggal, $absen) {
        $user = UserApi::getUsername($token);
        //$model = DataAbsenManualMobile::findOne(['NIP' => $user, 'tanggal' => $tanggal, 'absen' => $absen]);
        
        if ($model)
            return [
                'status' => 1,
                'origin' => $model->origin
            ];

        return ['status' => 0];
    }


    public function actionCheckAbsen($nip, $tanggal, $absen) {
        $data = DataAbsenManualMobile::findOne(['NIP' => $nip, 'tanggal' => $tanggal, 'absen' => $absen]);

        return $data;
    }


    public function actionAbsen() {
        $nip = $this->getNip(Yii::$app->request->post('nip'));
        $tanggal = Yii::$app->request->post('tanggal');
        $jam = Yii::$app->request->post('jam');
        $lat = Yii::$app->request->post('lat');
        $lng = Yii::$app->request->post('lng');
        $absen = Yii::$app->request->post('absen');

        $model = new DataAbsenManualMobile();

        $model->id = 0;
        $model->NIP = $nip->NIP;
        $model->tanggal = $tanggal;
        $model->jam = $jam;
        $model->location = $lat . ',' . $lng;
        $model->absen = $absen;
        $model->origin = 'A';

        if ($model->save())
            return ['status' => 1];

        return ['status' => 0];
    }


    public function actionAccount($token) {
        $user = UserApi::getUsername($token);

        return ['user' => $user];
    }


    public function actionJadwalWfh($token) {
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
        
        $user = $this->getNip($token);

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P0M'));
        $ym = $bulan->format('Y-m');

        $model = JadwalWFH::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$user->NIP])
            ->all();

        return [
            'nip' => $user->NIP,
            'wfh' => $model,
            'bulan' => $monthList[$bulan->format('m')],
            'tahun' => $bulan->format('Y'),
        ];
    }


    public function actionAbsenHariIni($token) {
        $user = $this->getNip($token);

        $tanggal = new \DateTime(null, new \DateTimeZone(TIMEZONE));
        $tgl = $tanggal->format('Y-m-d');

        $model = DataAbsenManualMobile::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$tgl, 'NIP'=>$user->NIP])
            ->all();

        return [
            'absen' => $model,
        ];
    }


    public function getNip($token) {
        return UserApi::getUsername($token);
    }


    public function actionKehadiran($token) {
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

        $user = $this->getNip($token);

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
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$user->NIP])
            ->all();

        return [
            'nip' => $user->NIP,
            'kehadiran' => $model,
            'bulan' => $monthList[$bulan->format('m')],
            'tahun' => $bulan->format('Y'),
        ];
    }


    public function actionTpp($token) {
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

        $user = $this->getNip($token);

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

        $model = PrestasiPerilakuKerja::find()
            ->where(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym, 'NIP'=>$user->NIP])
            ->one();

        // $bobot_kinerja = round($model->kinerja/JAM_KINERJA*BOBOT_KINERJA, 2);

        $jam_kinerja = ($model->kinerja >= JAM_KINERJA) ? JAM_KINERJA : $model->kinerja;
        $bobot_kinerja = round($jam_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2);

        $tpp_pegawai = ($model->nip->kodeTpp) ? $model->nip->kodeTpp->tpp : '';

        $gol = explode('/', $model->nip->gol_ruang);

        $tpp_sebelum_pajak = ($model->nip->kodeTpp) ? (($model->bobot_ketepatan + $model->bobot_kehadiran + $bobot_kinerja)/100) * $model->nip->kodeTpp->tpp : '';

        $pajak = ($model->nip->kodeTpp) ? ($gol[0] == 'III') ? 5/100 * (($model->bobot_ketepatan + $model->bobot_kehadiran + $bobot_kinerja)/100) * $model->nip->kodeTpp->tpp : (($gol[0] == 'IV') ? 15/100 * (($model->bobot_ketepatan + $model->bobot_kehadiran + $bobot_kinerja)/100) * $model->nip->kodeTpp->tpp : 0) : '';

        $round_pajak = round($pajak);

        $tpp_dibayar = $tpp_sebelum_pajak - $round_pajak;

        return [
            'nip' => $user->NIP,
            'tpp' => $model,
            'bulan' => $monthList[$bulan->format('m')],
            'tahun' => $bulan->format('Y'),
            'bobot_kinerja' => $bobot_kinerja,
            'tpp_pegawai' => Yii::$app->formatter->asDecimal($tpp_pegawai),
            'pajak' => Yii::$app->formatter->asDecimal($round_pajak),
            'tpp_sebelum_pajak' => Yii::$app->formatter->asDecimal($tpp_sebelum_pajak),
            'tpp_dibayar' => Yii::$app->formatter->asDecimal($tpp_dibayar),
        ];
    }


    public function actionUpdateStatus()
    {
        $id = Yii::$app->request->post('id');
        $lat = Yii::$app->request->post('lat');
        $lng = Yii::$app->request->post('lng');
        $tanggal_absen = Yii::$app->request->post('tanggal_absen');

        $model = $this->findModel($id);

        $model->status = 1;
        $model->current_location = $lat . ',' . $lng;
        $model->tanggal_absen = $tanggal_absen;

        if ($model->save())
            return ['status' => 1];

        return ['status' => 0];
    }


    public function actionUploadPhoto()
    {
        $user = $this->getNip(Yii::$app->request->post('jwt'));
        $id = Yii::$app->request->post('id');

        // $model = new TugasLuar();
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());

        $model->photo = UploadedFile::getInstanceByName('picture');

        if ($model->photo) {
            $model->photo->saveAs('../../admin/data/pictures/'.$user->NIP.'-'.$id.'.'.$model->photo->extension);
            $model->photo = $user->NIP.'-'.$id.'.'.$model->photo->extension;
        }


        if ($model->save()) {
            return ['data' => $model->attributes];
        }
        else {
            return ['data' => ''];
        }
    }


    public function actionGetTugasLuar($token) {
        $user = $this->getNip($token)->NIP;

        $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
        $bulan->sub(new \DateInterval('P0M'));
        $ym = $bulan->format('Y-m');

        $model = TugasLuar::find()
            ->where(['NIP' => $user])
            ->andWhere(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            // ->andWhere(['status' => 1])
            ->orderBy(['tanggal'=>SORT_ASC])
            ->all();

        return ['tl' => $model];
    }


    public function actionTugasLuar($token) {
        $user = $this->getNip($token)->NIP;

        $bulan = new \DateTime(null, new \DateTimeZone(TIMEZONE));
        $ymd = $bulan->format('Y-m-d');

        $model = TugasLuar::find()
            ->where(['NIP' => $user])
            ->andWhere(['DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])
            ->orderBy(['tanggal'=>SORT_ASC])
            ->all();

        return ['tl' => $model];
    }


    public function actionRadius() {
        $model = Config::find()->one();

        return ['radius' => $model->radius];
    }


    public function actionUpdateTugas() {
        TugasLuar::updateAll(['status' => 0]);
    }


    protected function findModel($id)
    {
        if (($model = TugasLuar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
