<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "satuan_kerja".
 *
 * @property string $kode
 * @property string $nama
 * @property string $alamat
 * @property string $telp
 *
 * @property Pegawai[] $pegawai
 */
class SatuanKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'satuan_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['kode'], 'string', 'max' => 2],
            [['nama', 'alamat'], 'string', 'max' => 255],
            [['telp'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'telp' => 'Telp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasMany(Pegawai::className(), ['kode_satker' => 'kode']);
    }
}
