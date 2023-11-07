<?php

namespace unit\models;

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
            [['file'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => false, 'extensions' => 'json'],
            //[['imageFile'], 'file', 'skipOnEmpty' => false, 'checkExtensionByMimeType' => false, 'extensions' => 'jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('@webroot/assets/file' . $this->file->extension);
            return true;
        }
        else {
            return false;
        }
    }

}
