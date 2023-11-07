<?php

namespace pegawai\controllers;

use Yii;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\PrestasiPerilakuKerja;
use common\models\Keterangan;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

/**
 * AbsenPegawaiController implements the CRUD actions for AbsenPegawai model.
 */
class PegawaiController extends Controller
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


    public function actionProfile()
    {
        $nip = Yii::$app->user->identity->NIP;

        return $this->render('profile', [
            'model' => $this->findModel($nip),
        ]);
    }


    public function actionKehadiran()
    {
        $nip = Yii::$app->user->identity->NIP;

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
            ->all();

        return $this->render('kehadiran', [
            'model'=>$model,
            'nip'=>$nip,
            'bulan'=>$ym,
        ]);
    }


    public function actionTpp()
    {
        $nip = Yii::$app->user->identity->NIP;

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
            ->where(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym, 'nip'=>$nip])
            ->all();

        return $this->render('tpp', [
            'model'=>$model,
            'nip'=>$nip,
            'bulan'=>$ym,
        ]);
    }


    public function actionDetailTpp($ym)
    {
        $nip = Yii::$app->user->identity->NIP;

        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        return $this->render('detail-tpp', [
            'model'=>$model,
            'nip'=>$nip,
            'bulan'=>$ym,
        ]);        
    }


    public function checkKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
        return $data;
    }




    protected function findModel($id)
    {
        if (($model = DataPegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
