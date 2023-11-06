<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unit_kerja".
 *
 * @property string $kode
 * @property string $nama
 * @property string $alamat
 * @property string $telp
 *
 * @property DataPegawai[] $dataPegawai
 */
class UnitKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_kerja';
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
    public function getDataPegawai()
    {
        return $this->hasMany(DataPegawai::className(), ['unit_kerja' => 'kode']);
    }
}
