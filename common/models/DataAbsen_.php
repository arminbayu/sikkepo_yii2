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
 * @property Pegawai $pegawai
 * @property Terminal $terminal
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
            [['pin', 'date_time', 'ver', 'status'], 'required'],
            [['kode_terminal', 'pin', 'ver', 'status'], 'integer'],
            [['date_time'], 'safe'],
            [['pin'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pin' => 'no_absen']],
            [['kode_terminal'], 'exist', 'skipOnError' => true, 'targetClass' => Terminal::className(), 'targetAttribute' => ['kode_terminal' => 'kode']],
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
        return $this->hasOne(Pegawai::className(), ['no_absen' => 'pin']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['kode' => 'kode_terminal']);
    }
}
