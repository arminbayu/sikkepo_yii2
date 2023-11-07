<?php

namespace admin\controllers;

use Yii;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\UnitKerja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['data-pegawai', 'cari-pegawai', 'pegawai', 'admin', 'admin-pegawai', 'data-pegawai-per-unit-kerja', 'tambah-pegawai', 'edit-kode-terminal', 'edit-no-absen', 'edit-tpp', 'edit-pegawai', 'delete-pegawai', 'detail-pegawai', 'update-status'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-pegawai' => ['POST'],
                    'update-status' => ['POST'],
                ],
            ],
        ];
    }



    public function actionDataPegawaiAll()
    {
        $model = DataPegawai::find();
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $page = 1;

        if (isset($_GET["page"]))
            $page = $_GET["page"];

        $limit = 10;
        $offset = $limit * ($page - 1);
        // $pageSize = ceil($countQuery->count() / $limit);
        $pages->setPageSize($limit);

        $model = $model->offset($offset) //$pageSize
            ->limit($limit)
            ->all();


        // $model = $model->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();

        return $this->render('data-pegawai-all', [
            'model' => $model,
            'pages' => $pages,
        ]);
    }



    public function actionDataPegawai()
    {
        $model = UnitKerja::find()->all();

        return $this->render('data-pegawai', [
            'model'=>$model,
        ]);
    }



    public function actionCariPegawai()
    {
        return $this->render('cari-pegawai');
    }



    public function actionPegawai($unit, $nama)
    {
        if ($nama == '')
            return $this->redirect(['pegawai', 'status' => 404]);

        if ($unit != '') {
            $model = DataPegawai::find()
              ->where(['unit_kerja'=>$unit])
              ->andFilterWhere(['like', 'nama', $nama])
              ->all();
        }
        else {
            $model = DataPegawai::find()
              ->where(['like', 'nama', $nama])
              ->all();
        }

        return $this->render('pegawai', [
            'model' => $model,
            'nama' => $nama,
        ]);
    }



    public function actionUpdateStatus($nip, $nama, $unit)
    {
        $model = $this->findModel($nip);

        $model->status = ($model->status == 0) ? 1 : 0;

        if ($model->save()) {
            return $this->redirect(['pegawai', 'nama' => $nama, 'unit' => $unit]);
        }
    }



    public function actionDataPegawaiPerUnitKerja($unit)
    {
        // DataPegawai::updateAll(['kode_tpp' => '00'], 'unit_kerja='.$unit);

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->orderBy(['eselon' => SORT_ASC]);

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
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

        return $this->render('data-pegawai-per-unit-kerja', [
            'model' => $model,
            'unit' => $unit,
            'pages' => $pages,
        ]);
    }



    public function actionTambahPegawai()
    {
        $model = new DataPegawai();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai']);
        } else {
            return $this->render('tambah-pegawai', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditKodeTerminal($nip, $unit)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai-per-unit-kerja', 'unit' => $unit]);
        } else {
            return $this->render('edit-kode-terminal', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditNoAbsen($nip, $unit)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai-per-unit-kerja', 'unit' => $unit]);
        } else {
            return $this->render('edit-no-absen', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditTpp($nip, $unit)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai-per-unit-kerja', 'unit' => $unit]);
        } else {
            return $this->render('edit-tpp', [
                'model' => $model,
            ]);
        }
    }



    public function actionEditPegawai($nip, $unit)
    {
        $model = $this->findModel($nip);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['data-pegawai-per-unit-kerja', 'unit' => $unit]);
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




    public function actionAdmin()
    {
        $model = UnitKerja::find()->all();

        return $this->render('admin', [
            'model'=>$model,
        ]);
    }

    // admin oegawai
    public function actionAdminPegawai($unit)
    {
        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit]);

        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $page = 1;

        if (isset($_GET["page"]))
            $page = $_GET["page"];

        $limit = 20;
        $offset = $limit * ($page - 1);
        // $pageSize = ceil($countQuery->count() / $limit);
        $pages->setPageSize($limit);

        $model = $model->offset($offset) //$pageSize
            ->limit($limit)
            ->all();


        // $model = $model->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();

        return $this->render('admin-pegawai', [
            'model' => $model,
            'unit' => $unit,
            'pages' => $pages,
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
