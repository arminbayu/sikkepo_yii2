<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_absen_mixed".
 *
 * @property int $id
 * @property string $NIP
 * @property string $tanggal
 * @property string $jam
 * @property string $location
 * @property string $absen
 * @property string $keterangan
 * @property string $origin
 */
class DataAbsenManualMobile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_absen_mixed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal', 'jam'], 'safe'],
            [['NIP'], 'string', 'max' => 18],
            [['location', 'keterangan'], 'string', 'max' => 64],
            [['absen'], 'string', 'max' => 8],
            [['origin'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'NIP' => 'N I P',
            'tanggal' => 'Tanggal',
            'jam' => 'Jam',
            'location' => 'Location',
            'absen' => 'Absen',
            'keterangan' => 'Keterangan',
            'origin' => 'Origin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNip()
    {
        return $this->hasOne(DataPegawai::className(), ['NIP' => 'NIP']);
    }
}
