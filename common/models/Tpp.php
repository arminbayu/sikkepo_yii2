<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tpp".
 *
 * @property string $kode
 * @property string $eselon
 * @property string $golongan
 * @property int $beban_kerja
 * @property int $prestasi_kerja
 * @property int $uang_makan
 */
class Tpp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tpp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['beban_kerja', 'prestasi_kerja', 'uang_makan'], 'integer'],
            [['kode'], 'string', 'max' => 2],
            [['eselon', 'golongan'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'eselon' => 'Eselon',
            'golongan' => 'Golongan',
            'beban_kerja' => 'Beban Kerja',
            'prestasi_kerja' => 'Prestasi Kerja',
            'uang_makan' => 'Uang Makan',
        ];
    }
}
