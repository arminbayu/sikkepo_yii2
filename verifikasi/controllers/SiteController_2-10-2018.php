<?php
namespace admin\controllers;

use Yii;
use admin\models\TarikDataAbsen;
use common\models\Terminal;
use common\models\DataAbsen;
use common\models\DataPegawai;
use common\models\UserPegawai;
use common\models\UserUnit;
use common\models\Staff;
use common\models\Unit;
use common\models\LoginForm;
use common\models\UploadData;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'pengguna', 'tambah-pengguna', 'edit-pengguna', 'hapus-pengguna', 'terminal', 'tambah-terminal', 'edit-terminal', 'daftar-terminal', 'ambil-data', 'data-absen', 'file-data-absen', 'no-absen', 'ka-unit', 'tambah-ka-unit', 'edit-ka-unit', 'hapus-ka-unit', 'cari-data-absen', 'pegawai', 'data-absen-pegawai', 'import-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['kehadiran/kehadiran-per-unit-kerja']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // return $this->goHome();
            return $this->redirect(['kehadiran/kehadiran-per-unit-kerja']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->goBack();
            return $this->redirect(['kehadiran/kehadiran-per-unit-kerja']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionPengguna()
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => UserPegawai::find(),
        // ]);
        $dataProvider = UserPegawai::find()->all();

        return $this->render('pengguna', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionTambahPengguna()
    {
        $model = new Staff();

        if (Yii::$app->request->post()) {
            $model->username = Yii::$app->request->post('username');
            $model->email = Yii::$app->request->post('email');
            $model->setPassword(Yii::$app->request->post('password_hash'));
            $model->generateAuthKey();
            $model->save();
            return $this->redirect(['pengguna']);
        } else {
            return $this->render('tambah-pengguna', [
                'model' => $model,
            ]);
        }
    }


    public function actionEditPengguna($id)
    {
        $model = $this->findPengguna($id);

        if (Yii::$app->request->post()) {
            $model->email = Yii::$app->request->post('email');
            if (Yii::$app->request->post('password_hash') != '') {
                $model->setPassword(Yii::$app->request->post('password_hash'));
                $model->generateAuthKey();
            }
            $model->save();

            return $this->redirect(['pengguna']);
        } else {
            return $this->render('edit-pengguna', [
                'model' => $model,
            ]);
        }
    }


    public function actionHapusPengguna($id)
    {
        $this->findPengguna($id)->delete();

        return $this->redirect(['pengguna']);
    }




    public function actionKaUnit()
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => UserPegawai::find(),
        // ]);
        $dataProvider = UserUnit::find()->all();

        return $this->render('ka-unit', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionTambahKaUnit()
    {
        $model = new Unit();

        if (Yii::$app->request->post()) {
            $model->username = Yii::$app->request->post('username');
            $model->email = Yii::$app->request->post('email');
            $model->setPassword(Yii::$app->request->post('password_hash'));
            $model->generateAuthKey();
            $model->save();
            return $this->redirect(['ka-unit']);
        } else {
            return $this->render('tambah-ka-unit', [
                'model' => $model,
            ]);
        }
    }


    public function actionEditKaUnit($id)
    {
        $model = $this->findKaUnit($id);

        if (Yii::$app->request->post()) {
            $model->email = Yii::$app->request->post('email');
            if (Yii::$app->request->post('password_hash') != '') {
                $model->setPassword(Yii::$app->request->post('password_hash'));
                $model->generateAuthKey();
            }
            $model->save();

            return $this->redirect(['ka-unit']);
        } else {
            return $this->render('edit-ka-unit', [
                'model' => $model,
            ]);
        }
    }


    public function actionHapusKaUnit($id)
    {
        $this->findKaUnit($id)->delete();

        return $this->redirect(['ka-unit']);
    }


    public function actionTerminal()
    {
        $model = Terminal::find()->all();

        return $this->render('terminal', [
            'model'=>$model,
        ]);
    }

    public function actionTambahTerminal()
    {
        $model = new Terminal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Terminal telah ditambahkan.');
            return $this->redirect(['terminal']);
        } else {
            return $this->render('tambah-terminal', [
                'model' => $model,
            ]);
        }
    }

    public function actionEditTerminal($kode)
    {
        $model = $this->findTerminal($kode);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['terminal']);
        } else {
            return $this->render('edit-terminal', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteTerminal($kode)
    {
        $this->findTerminal($kode)->delete();

        return $this->redirect(['terminal']);
    }

    public function actionDaftarTerminal()
    {
        $model = Terminal::find()->all();

        return $this->render('daftar-terminal', [
            'model'=>$model,
        ]);
    }


    protected function parseData($data, $p1, $p2) {
        $data = " " . $data;
        $result = "";
        $start = strpos($data, $p1);
        if ($start != "") {
        $end = strpos(strstr($data, $p1), $p2);
            if ($end != ""){
                $result = substr($data, $start+strlen($p1), $end-strlen($p1));
            }
        }
        return $result; 
    }


    public function checkData($pin, $dateTime) {
        $data = DataAbsen::find()->where(['pin'=>$pin, 'date_time'=>$dateTime])->exists();
        return $data;
    }


    public function checkDataAbsen($kodeTerminal, $pin, $dateTime) {
        $data = DataAbsen::find()->where(['kode_terminal'=>$kodeTerminal, 'pin'=>$pin, 'date_time'=>$dateTime])->exists();
        return $data;
    }


    public function actionAmbilData($kode)
    {
        $model = $this->findTerminal($kode);

        $ip = $model->ip_address;
        $key = "0";
        $buffer = "";
        $status = "";
        $i = 0;

        // if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
        // {
        //     $errorcode = socket_last_error();
        //     $errormsg = socket_strerror($errorcode);
             
        //     die("Couldn't create socket: [$errorcode] $errormsg");
        // }
        // echo "Socket created";

        // if(!socket_connect($sock , $ip , 80))
        // {
        //     $errorcode = socket_last_error();
        //     $errormsg = socket_strerror($errorcode);
             
        //     die("Could not connect: [$errorcode] $errormsg \n");
        // }
         
        // echo "Connection established \n";

        if ($ip != "") {
            $connect = @fsockopen($ip, "80", $errno, $errstr, 1);
            if ($connect) {
                $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
                $newLine = "\r\n";

                fputs($connect, "POST /iWsService HTTP/1.0".$newLine);
                fputs($connect, "Content-Type: text/xml".$newLine);
                fputs($connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
                fputs($connect, $soap_request.$newLine);
                
                while ($response = fgets($connect, 1024)) {
                    $buffer = $buffer . $response;
                }

                $buffer = $this->parseData($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
                $buffer = explode("\r\n", $buffer);

                for ($a=0; $a<count($buffer); $a++) {
                    $data = $this->parseData($buffer[$a], "<Row>", "</Row>");
                    $pin = $this->parseData($data, "<PIN>", "</PIN>");
                    $dateTime = $this->parseData($data, "<DateTime>", "</DateTime>");
                    $verified = $this->parseData($data, "<Verified>", "</Verified>");
                    $status = $this->parseData($data, "<Status>", "</Status>");

                    if (!$this->checkDataAbsen($kode, $pin, $dateTime) && $pin && $dateTime) {
                        try {
                            $absen = new TarikDataAbsen;
                            $absen->id = 0;
                            $absen->kode_terminal = $model->kode;
                            $absen->pin = $pin;
                            $absen->date_time = $dateTime;
                            $absen->ver = $verified;
                            $absen->status = $status;
                            $absen->save();
                            $i++;
                        }
                        catch (IntegrityException $e) {
                            // throw new \yii\web\HttpException(500, "YOUR MESSAGE.", 405);
                            Yii::$app->session->setFlash('danger', 'PIN ' . $pin . ' tidak ada dalam Data Pegawai!');
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Waktu proses: '.gmdate('H:i:s', Yii::getLogger()->getElapsedTime()) . '. ' . $i . ' records berhasil ditambahkan.');
            }
            else {
                Yii::$app->session->setFlash('danger', 'Koneksi gagal!');
            }
        }

        $model = Terminal::find()->all();

        return $this->render('daftar-terminal', [
            'model'=>$model,
        ]);
    }

    public function actionDataAbsen()
    {
        $model = DataAbsen::find();
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

        $page = 1;

        if (isset($_GET["page"]))
            $page = $_GET["page"];

        $limit = 100;
        $offset = $limit * ($page - 1);
        //$pageSize = ceil($countQuery->count() / $limit);
        $pages->setPageSize($limit);

        $model = $model->offset($offset)
            ->limit($limit)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('data-absen', [
            'model'=>$model,
            'pages' => $pages,
        ]);


        // $dataProvider = new ActiveDataProvider([
        //     'query' => DataAbsen::find(),
        //     'pagination' => [
        //         'pageSize' => 5,
        //     ],
        // ]);

        // return $this->render('data-absen', [
        //     'dataProvider' => $dataProvider,
        // ]);
    }


    public function actionFileDataAbsen()
    {
        $file = UploadData::find()
            ->all();

        return $this->render('file-data-absen', [
            'file'=>$file,
        ]);
    }


    public function actionCariDataAbsen()
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            // $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        return $this->render('cari-pegawai', [
            'bulan' => $ym,
        ]);
    }


    public function actionPegawai($unit, $nama)
    {
        if ($nama == '')
            return $this->redirect(['pegawai', 'status' => 404]);

        $model = DataPegawai::find()
          ->where(['unit_kerja'=>$unit])
          ->andFilterWhere(['like', 'nama', $nama])
          ->all();

        return $this->render('pegawai', [
            'model' => $model,
            'nama' => $nama,
        ]);
    }


    public function actionDataAbsenPegawai($kode, $pin, $month, $year)
    {
        $ym = $year.'-'.$month;

        $model = DataAbsen::find()
            ->where(['data_absen.kode_terminal'=>$kode])
            ->andWhere(['pin'=>$pin])
            ->andWhere(['DATE_FORMAT(date_time, "%Y-%m")'=>$ym])
            ->innerJoinWith(['pegawai']);

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


    public function actionNoAbsen()
    {
        $model = DataAbsen::find()
            ->select('pin, kode_terminal')
            ->distinct()
            ->all();

        return $this->render('no-absen', [
            'model'=>$model,
        ]);
    }


    public function actionImportData($terminal, $ym)
    {
        $i = 0;
        $jsonFile = Yii::getAlias('@admin/data/'.$terminal.'/').$ym.'-'.$terminal.'.json';

        try {
            $requestObject = Json::decode(file_get_contents($jsonFile), false);
        }
        catch (InvalidParamException $e) {
            $requestObject = null;
        }

        $isBatch = is_array($requestObject);
        $requests = $isBatch ? $requestObject : [$requestObject];
        $resultData = null;
        if (empty($requests)) {
            $isBatch = false;
            $resultData = [Helper::formatResponse(null, new Exception("Invalid Request", Exception::INVALID_REQUEST))];
            Yii::$app->session->setFlash('danger', 'File kosong!');
        }
        else {
            foreach ($requests as $request) {
                $kode = $request->kode_terminal;
                $pin = $request->pin;
                $dateTime = $request->date_time;
                $verified = $request->ver;
                $status = $request->status;

                if (!$this->checkDataAbsen($kode, $pin, $dateTime) && $pin && $dateTime) {
                    // echo $dateTime.'<br>';
                    try {
                        $absen = new TarikDataAbsen;
                        $absen->id = 0;
                        $absen->kode_terminal = $kode;
                        $absen->pin = $pin;
                        $absen->date_time = $dateTime;
                        $absen->ver = $verified;
                        $absen->status = $status;
                        $absen->save();
                        $i++;
                    }
                    catch (IntegrityException $e) {
                        // throw new \yii\web\HttpException(500, "YOUR MESSAGE.", 405);
                        Yii::$app->session->setFlash('danger', 'PIN ' . $pin . ' tidak ada dalam Data Pegawai!');
                    }
                }
            }
            UploadData::updateAll(['status' => 1], ['=', 'file', $ym.'.json']);
        }
        // $response = Yii::$app->getResponse();
        // $response->format = Response::FORMAT_JSON;
        // $response->data = $isBatch || null === $resultData ? $resultData : current($resultData);
        // return $response;
            // print_r($requestObject);

        Yii::$app->session->setFlash('success', 'Waktu proses: '.gmdate('H:i:s', Yii::getLogger()->getElapsedTime()) . '. ' . $i . ' records berhasil ditambahkan.');

        return $this->redirect(['file-data-absen']);
    }


    protected function findTerminal($kode)
    {
        if (($model = Terminal::findOne($kode)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPengguna($id)
    {
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findKaUnit($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
