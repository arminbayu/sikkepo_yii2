<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "mahasiswa".
 *
 * @property string $nim
 * @property string $nama
 * @property string $jurusan
 */
class Mahasiswa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mahasiswa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nim'], 'required'],
            [['nim'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 64],
            [['jurusan'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nim' => 'NIM',
            'nama' => 'Nama',
            'jurusan' => 'Jurusan',
        ];
    }
}
