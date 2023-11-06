<?php

namespace admin\controllers;

use Yii;
use common\models\Terminal;
use common\models\HariLibur;
use common\models\UnitKerja;
use common\models\DataKeterangan;
use common\models\DataTpp;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * HariLiburController implements the CRUD actions for HariLibur model.
 */
class DataController extends Controller
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
                        'actions' => ['unit-kerja-api', 'terminal-api'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['hari-libur', 'view-hari-libur', 'add-hari-libur', 'edit-hari-libur', 'delete-hari-libur', 'unit-kerja', 'view-unit-kerja', 'add-unit-kerja', 'edit-unit-kerja', 'delete-unit-kerja', 'keterangan', 'view-keterangan', 'add-keterangan', 'edit-keterangan', 'delete-keterangan', 'tpp', 'view-tpp', 'add-tpp', 'edit-tpp', 'delete-tpp'],
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

    /**
     * Lists all HariLibur models.
     * @return mixed
     */
    public function actionHariLibur()
    {
        $tahun = new \DateTime('now', new \DateTimeZone(TIMEZONE));
        $y = $tahun->format('Y');

        $model = HariLibur::find()
            ->where(['DATE_FORMAT(tanggal, "%Y")'=>$y])
            ->orderBy('tanggal')
            ->all();

        return $this->render('hari-libur', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single HariLibur model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewHariLibur($id)
    {
        return $this->render('view', [
            'model' => $this->findModelHariLibur($id),
        ]);
    }

    /**
     * Creates a new HariLibur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddHariLibur()
    {
        $model = new HariLibur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['hari-libur']);
        } else {
            return $this->render('add-hari-libur', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing HariLibur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEditHariLibur($id)
    {
        $model = $this->findModelHariLibur($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['hari-libur']);
        } else {
            return $this->render('edit-hari-libur', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing HariLibur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteHariLibur($id)
    {
        $this->findModelHariLibur($id)->delete();

        return $this->redirect(['hari-libur']);
    }


    /**
     * Lists all SatuanKerja models.
     * @return mixed
     */
    public function actionUnitKerja()
    {
        $model = UnitKerja::find()->all();

        return $this->render('unit-kerja', [
            'model' => $model,
        ]);
    }


    /**
     * Lists all SatuanKerja models.
     * @return mixed
     */
    public function actionUnitKerjaApi()
    {
        $model = UnitKerja::find()->all();

        $jsonData = [];

        foreach ($model as $data) {
            $jsonData[] = $data;
        }

        $jsonDataStream = Json::encode($jsonData);

        return $jsonDataStream;
    }


    /**
     * Lists all SatuanKerja models.
     * @return mixed
     */
    public function actionTerminalApi()
    {
        $model = Terminal::find()->all();

        $jsonData = [];

        foreach ($model as $data) {
            $jsonData[] = $data;
        }

        $jsonDataStream = Json::encode($jsonData);

        return $jsonDataStream;
    }


    /**
     * Displays a single SatuanKerja model.
     * @param string $id
     * @return mixed
     */
    public function actionViewUnitKerja($id)
    {
        return $this->render('view', [
            'model' => $this->findModelUnitKerja($id),
        ]);
    }

    /**
     * Creates a new SatuanKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddUnitKerja()
    {
        $model = new UnitKerja();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['unit-kerja']);
        } else {
            return $this->render('add-unit-kerja', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SatuanKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionEditUnitKerja($id)
    {
        $model = $this->findModelUnitKerja($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['unit-kerja']);
        } else {
            return $this->render('edit-unit-kerja', [
                'model' => $model,
                'id' => $id,
            ]);
        }
    }

    /**
     * Deletes an existing SatuanKerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteUnitKerja($id)
    {
        $this->findModelUnitKerja($id)->delete();

        return $this->redirect(['unit-kerja']);
    }


    
    /**
     * Lists all StatusKehadiran models.
     * @return mixed
     */
    public function actionKeterangan()
    {
        $model = DataKeterangan::find()->all();

        return $this->render('keterangan', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single StatusKehadiran model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewKeterangan($id)
    {
        return $this->render('view-keterangan', [
            'model' => $this->findModelKeterangan($id),
        ]);
    }

    /**
     * Creates a new StatusKehadiran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddKeterangan()
    {
        $model = new DataKeterangan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['keterangan']);
        } else {
            return $this->render('add-keterangan', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StatusKehadiran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEditKeterangan($id)
    {
        $model = $this->findModelKeterangan($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['keterangan']);
        } else {
            return $this->render('edit-keterangan', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StatusKehadiran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteKeterangan($id)
    {
        $this->findModelKeterangan($id)->delete();

        return $this->redirect(['keterangan']);
    }




    /**
     * Lists all StatusKehadiran models.
     * @return mixed
     */
    public function actionTpp()
    {
        $model = DataTpp::find()
            ->where(['<>', 'kode', '00'])
            ->orderBy('kode')
            ->all();

        return $this->render('tpp', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single StatusKehadiran model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewTpp($kode)
    {
        return $this->render('view-tpp', [
            'model' => $this->findModelTpp($id),
        ]);
    }

    /**
     * Creates a new StatusKehadiran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddTpp()
    {
        $model = new DataTpp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tpp']);
        } else {
            return $this->render('add-tpp', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StatusKehadiran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEditTpp($kode)
    {
        $model = $this->findModelTpp($kode);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tpp']);
        } else {
            return $this->render('edit-tpp', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StatusKehadiran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteTpp($kode)
    {
        $this->findModelTpp($kode)->delete();

        return $this->redirect(['tpp']);
    }


    /**
     * Finds the HariLibur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HariLibur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelHariLibur($id)
    {
        if (($model = HariLibur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the SatuanKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SatuanKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelUnitKerja($id)
    {
        if (($model = UnitKerja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the StatusKehadiran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StatusKehadiran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelKeterangan($id)
    {
        if (($model = DataKeterangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the StatusKehadiran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StatusKehadiran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelTpp($kode)
    {
        if (($model = DataTpp::findOne($kode)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
