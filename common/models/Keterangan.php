<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "keterangan".
 *
 * @property int $id
 * @property string $NIP
 * @property string $no_sk
 * @property string $tanggal
 * @property int $keterangan
 *
 * @property DataKeterangan $data
 * @property Pegawai $nip
 */
class Keterangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keterangan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal'], 'safe'],
            [['keterangan'], 'integer'],
            [['NIP'], 'string', 'max' => 18],
            [['no_sk'], 'string', 'max' => 64],
            [['keterangan'], 'exist', 'skipOnError' => true, 'targetClass' => DataKeterangan::className(), 'targetAttribute' => ['keterangan' => 'id']],
            [['NIP'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['NIP' => 'NIP']],
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
            'no_sk' => 'No SK',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(DataKeterangan::className(), ['id' => 'keterangan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNip()
    {
        return $this->hasOne(Pegawai::className(), ['NIP' => 'NIP']);
    }
}
