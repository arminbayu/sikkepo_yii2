<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_absen".
 *
 * @property int $id
 * @property int $kode_terminal
 * @property int $pin
 * @property string $date_time
 * @property int $ver
 * @property int $status
 *
 * @property DataPegawai $pegawai
 */
class DataAbsen extends \yii\db\ActiveRecord
{
    public $date, $min_time, $mid_time, $max_time;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_absen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_terminal', 'pin', 'ver', 'status'], 'integer'],
            [['date_time', 'ver', 'status'], 'required'],
            [['date_time'], 'safe'],
            [['kode_terminal', 'pin'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['kode_terminal' => 'kode_terminal', 'pin' => 'no_absen']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_terminal' => 'Kode Terminal',
            'pin' => 'PIN',
            'date_time' => 'Date Time',
            'ver' => 'Ver',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(DataPegawai::className(), ['kode_terminal' => 'kode_terminal', 'no_absen' => 'pin']);
    }
}
