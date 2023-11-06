<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "terminal".
 *
 * @property int $kode
 * @property string $ip_address
 * @property string $nama
 * @property string $kode_satker
 */
class Terminal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'integer'],
            [['ip_address'], 'string', 'max' => 16],
            [['nama'], 'string', 'max' => 32],
            [['unit_kerja'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'ip_address' => 'IP Address',
            'nama' => 'Nama',
            'unit_kerja' => 'Unit Kerja',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(UnitKerja::className(), ['kode' => 'unit_kerja']);
    }
}
