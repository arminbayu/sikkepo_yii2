<?php

namespace unit\controllers;

use Yii;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\UnitKerja;
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



    public function getUnit() {
        return Yii::$app->user->identity->pegawai->unit_kerja;
    }



    public function actionDataPegawai()
    {
        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$this->unit, 'status'=>1])
            ->orderBy(['eselon' => SORT_ASC]);

        $unit = UnitKerja::find()
            ->where(['kode'=>$this->unit])
            ->one();

        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $page = 1;

        if (isset($_GET["page"]))
            $page = $_GET["page"];

        $limit = 30;
        $offset = $limit * ($page - 1);
        // $pageSize = ceil($countQuery->count() / $limit);
        $pages->setPageSize($limit);

        $model = $model->offset($offset) //$pageSize
            ->limit($limit)
            ->all();


        // $model = $model->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();

        return $this->render('data-pegawai', [
            'model' => $model,
            'unit' => $unit,
            'pages' => $pages,
        ]);
    }



    public function actionCariPegawai()
    {
        return $this->render('cari-pegawai');
    }



    public function actionPegawai($nama)
    {
        if ($nama == '')
            return $this->redirect(['pegawai', 'status' => 404]);

        $model = DataPegawai::find()
          ->where(['unit_kerja'=>$this->unit])
          ->andFilterWhere(['like', 'nama', $nama])
          ->all();

        return $this->render('pegawai', [
            'model' => $model,
            'nama' => $nama,
        ]);
    }



    public function actionUpdateStatus($nip, $nama)
    {
        $model = $this->findModel($nip);

        $model->status = ($model->status == 0) ? 1 : 0;

        if ($model->save()) {
            return $this->redirect(['pegawai', 'nama' => $nama]);
        }
    }



    public function actionTambahPegawai()
    {
        $model = new DataPegawai();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('tambah-pegawai', [
                'model' => $model,
                'unit' => $this->unit,
            ]);
        }
    }



    public function actionEditKodeTerminal($nip)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('edit-kode-terminal', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditNoAbsen($nip)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('edit-no-absen', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditTpp($nip)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('edit-tpp', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditPegawai($nip)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('edit-pegawai', [
                'model' => $model,
            ]);
        }
    }



    public function actionDeletePegawai($nip)
    {
        $this->findModel($nip)->delete();

        return $this->redirect(['data-pegawai']);
    }



    public function actionDetailPegawai($nip)
    {
        return $this->render('detail-pegawai', [
            'model' => $this->findModel($nip),
        ]);
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
