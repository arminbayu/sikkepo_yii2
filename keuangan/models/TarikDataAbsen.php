<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "data_absen".
 *
 * @property int $id
 * @property int $pin
 * @property string $date_time
 * @property int $ver
 * @property int $status
 * @property int $id_terminal
 *
 */
class TarikDataAbsen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_absen';
    }

}
