<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "status_kehadiran".
 *
 * @property int $kode
 * @property string $status
 */
class StatusKehadiran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status_kehadiran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'status' => 'Status',
        ];
    }
}
