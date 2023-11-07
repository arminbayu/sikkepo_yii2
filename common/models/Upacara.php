<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "upacara".
 *
 * @property int $id
 * @property string $NIP
 * @property string $tanggal
 * @property int $status
 */
class Upacara extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upacara';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal'], 'required'],
            [['status'], 'integer'],
            [['tanggal'], 'safe'],
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
            'status' => 'Status',
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
