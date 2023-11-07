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
            'except' => ['index', 'kehadiran', 'tpp', 'upload-photo', 'get-tugas-luar', 'update-status', 'update-tugas', 'radius'] //action that you don't want to authenticate such as login
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

        return ['user' => $user, 'unit' => $user->unitKerja->nama];
    }


    public function actionAccount($token) {
        $user = UserApi::getUsername($token);

        return ['user' => $user];
    }


    public function getNip($token) {
        return UserApi::getUsername($token);
    }


    public function actionKehadiran($token) {
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
            'bulan' => $bulan->format('F Y'),
        ];
    }


    public function actionTpp($token) {
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

        // $bobot_kinerja = round($model->bobot_kinerja/JAM_KINERJA*BOBOT_KINERJA, 2);
        $jam_kinerja = ($model->bobot_kinerja >= JAM_KINERJA) ? JAM_KINERJA : $model->bobot_kinerja;
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
            'bulan' => $bulan->format('F Y'),
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

        if ($model->save()) {
            return ['data' => $model->status];
        }
        else {
            return ['data' => ''];
        }
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
