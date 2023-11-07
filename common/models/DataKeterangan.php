<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_keterangan".
 *
 * @property int $id
 * @property string $keterangan
 *
 * @property Keterangan[] $keterangans
 */
class DataKeterangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_keterangan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keterangan'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeterangans()
    {
        return $this->hasMany(Keterangan::className(), ['keterangan' => 'id']);
    }
}
