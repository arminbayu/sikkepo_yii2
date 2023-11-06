<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_absen_manual".
 *
 * @property int $id
 * @property string $NIP
 * @property string $tanggal
 * @property string $jam
 * @property int $absen
 * @property int $keterangan
 */
class DataAbsenManual extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_absen_manual';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['absen', 'keterangan'], 'integer'],
            [['tanggal', 'jam'], 'safe'],
            [['NIP'], 'string', 'max' => 18],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'NIP' => 'NIP',
            'tanggal' => 'Tanggal',
            'jam' => 'Jam',
            'absen' => 'Absen',
            'keterangan' => 'Keterangan',
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
