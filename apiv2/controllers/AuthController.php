<?php
namespace apiv2\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;
use apiv2\models\LoginForm;
use apiv2\models\UserApi;


/**
 * AbsenPegawaiController implements the CRUD actions for AbsenPegawai model.
 */
class AuthController extends Controller
{    
    /**
     * @inheritdoc
     */
    public function behaviors(){
        // $behaviors = parent::behaviors();
        
        // // unset / hapus authenticator
        // unset($behaviors['authenticator']);
        
        // // tambahkan cors filter
        // $behaviors['corsFilter'] = [
        //     'class' => Cors::className(),
        // ];
        
        // return $behaviors;

        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['login', 'change-password'] //action that you don't want to authenticate such as login
        ];

        return $behaviors;
    }


    public function verbs(){
        // validasi http verbs untuk action signup dan login
        $verbs = [
            'login' => ['POST']
        ];
        
        return $verbs;
    }


    public function actionLogin() {
        $model = new LoginForm();
        
        // load data dari POST request
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($jwt = $model->login()) {
            return $jwt;
        }
    }


    public function actionChangePassword()
    {
        if (Yii::$app->request->post()) {
            $model = UserApi::getUsername(Yii::$app->request->post('token'));
            if (Yii::$app->request->post('password_hash') != '') {
                $model->setPassword(Yii::$app->request->post('password_hash'));
            }

            if ($model->save()) {
                return ['data' => 'Sukses ubah password.'];
            }
            else {
                return ['data' => ''];
            }
        }
    }


}
