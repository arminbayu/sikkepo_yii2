<?php

namespace participant\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
    public $file;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => false, 'extensions' => 'doc, docx'],
            //[['imageFile'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => false, 'extensions' => 'jpg'],
        ];
    }

    public function upload()
    {
        $id = Yii::$app->user->identity->username;

        if ($this->validate()) {
            $this->file->saveAs('paper/F' . $id . '.' . $this->file->extension);
            return true;
        }
        else {
            return false;
        }
    }

}
