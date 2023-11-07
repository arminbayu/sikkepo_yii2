<?php
namespace apiv2\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
use \Firebase\JWT\JWT;
use common\models\UnitKerja;

/**
 * Staff model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class UserApi extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%data_pegawai}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['NIP'], 'unique', 'targetAttribute' => ['NIP'], 'message' => '{attribute} {value} sudah terdaftar.'],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        
        // remove fields that contain sensitive information
        unset($fields['password_hash']);
        
        return $fields;
    }

    public static function getUsername($token)
    {
        $secret = base64_encode(Yii::$app->params['JWT']['secret_key']);

        $jwt = JWT::decode($token, $secret, [Yii::$app->params['JWT']['algorithm']]);
        
        return static::findIdentity($jwt->jti);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['NIP' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $secret = base64_encode(Yii::$app->params['JWT']['secret_key']);
        
        try {
            // decode token
            $jwt = JWT::decode($token, $secret, [Yii::$app->params['JWT']['algorithm']]);
            
            // temukan user berdasrkan jti / id user
            
            return static::findOne($jwt->jti);
            
        }
        catch(\Exception $e) {
            // throw UnauthorizedHttpException jika token tidak valid
            throw new UnauthorizedHttpException(Yii::t('yii', 'Invalid or Expired Token'));
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['NIP' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates JWT
     */
    public function generateJWT() {
        // configurasi JWT Token
        $secret = base64_encode(Yii::$app->params['JWT']['secret_key']);
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expiredTime = $issuedAt + (3600 * 24 * 30); // Expired dalam 30 hari
        $hostInfo = Yii::$app->request->hostInfo;
        
        $token = [
            'iat' => $issuedAt,        // Issued at: waktu saat token digenerate
            'jti' => $this->getId(),   // Json Token Id: user id
            'iss' => $hostInfo,        // Issuer : domain dimana token digenerate/dibuat
            'aud' => $hostInfo,        // audience : domain dimana token bisa digunakan
            'nbf' => $notBefore,       // Not before : token tidak dapat digunakan sebelum
            'exp' => $expiredTime,     // Expired time : waktu token expired
        ];
        
        return JWT::encode($token, $secret, Yii::$app->params['JWT']['algorithm']);
    }


    public function getUnitKerja()
    {
        return $this->hasOne(UnitKerja::className(), ['kode' => 'unit_kerja']);
    }


}
