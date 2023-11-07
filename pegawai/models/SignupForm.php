<?php
namespace participant\models;

use yii\base\Model;
use common\models\PUser;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $category;
    public $institution;
    public $city;
    public $password;
    public $password_repeat;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // ['username', 'trim'],
            // ['username', 'required'],
            // ['username', 'unique', 'targetClass' => '\common\models\PUser', 'message' => 'This username has already been taken.'],
            // ['username', 'string', 'min' => 2, 'max' => 255],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 15],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 64],
            ['email', 'unique', 'targetClass' => '\participant\models\Participant', 'message' => 'This email address has already been taken.'],

            ['institution', 'trim'],
            ['institution', 'required'],
            ['institution', 'string', 'max' => 255],

            ['city', 'trim'],
            ['city', 'required'],
            ['city', 'string', 'max' => 255],

            ['category', 'required'],
            ['category', 'integer'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $participant = new Participant();
        $participant->name = $this->name;
        $participant->phone = $this->phone;
        $participant->email = $this->email;
        $participant->institution = $this->institution;
        $participant->city = $this->city;
        $participant->category = $this->category;
        $participant->save();

        $user = new PUser();
        $user->username = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
