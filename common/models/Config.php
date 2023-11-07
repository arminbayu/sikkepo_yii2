<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property int $radius
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'radius', 'location_status'], 'integer'],
            [['awal_m', 'akhir_m', 'awal_s', 'akhir_s', 'awal_p', 'akhir_p'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'radius' => 'Radius',
            'location_status' => 'Koordinat Lokasi',
            'awal_m' => 'Batas Awal Absen Masuk',
            'akhir_m' => 'Batas Akhir Absen Masuk',
            'awal_s' => 'Batas Awal Absen Siang',
            'akhir_s' => 'Batas Akhir Absen Siang',
            'awal_p' => 'Batas Awal Absen Pulang',
            'akhir_p' => 'Batas Akhir Absen Pulang',
        ];
    }
}
