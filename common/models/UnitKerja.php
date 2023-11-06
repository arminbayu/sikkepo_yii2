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
 * @property string $jab_pl
 * @property string $koordinat
 * @property string $ka_unit
 * @property string $bendahara
 *
 * @property DataPegawai[] $dataPegawai
 * @property DataPegawai $kaUnit
 * @property DataPegawai $bendahara
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
            [['jab_pl', 'koordinat'], 'string', 'max' => 64],
            [['ka_unit', 'bendahara'], 'string', 'max' => 18],
            [['jab_pl'], 'filter', 'filter' => 'strtoupper'],
            [['ka_unit'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['ka_unit' => 'NIP']],
            [['bendahara'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['bendahara' => 'NIP']],
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
            'jab_pl' => 'Jabatan Penandatangan Laporan',
            'koordinat' => 'Koordinat',
            'ka_unit' => 'Ka Unit',
            'bendahara' => 'Bendahara',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataPegawai()
    {
        return $this->hasMany(DataPegawai::className(), ['unit_kerja' => 'kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaUnit()
    {
        return $this->hasOne(DataPegawai::className(), ['NIP' => 'ka_unit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBenUnit()
    {
        return $this->hasOne(DataPegawai::className(), ['NIP' => 'bendahara']);
    }
}
