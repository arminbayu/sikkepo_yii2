<?php

namespace common\models;

use Yii;

Yii::$app->formatter->locale = 'id-ID';

/**
 * This is the model class for table "tpp".
 *
 * @property string $kode
 * @property string $eselon
 * @property string $golongan
 * @property int $tpp
 * @property int $beban_kerja
 * @property int $prestasi_kerja
 * @property int $uang_makan
 * @property int $keterangan
 */
class DataTpp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_tpp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['tpp', 'beban_kerja', 'prestasi_kerja', 'uang_makan'], 'integer'],
            [['kode'], 'string', 'max' => 2],
            [['eselon', 'golongan'], 'string', 'max' => 8],
            [['keterangan'], 'string', 'max' => 64],
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
            'tpp' => 'TPP',
            'beban_kerja' => 'Beban Kerja',
            'prestasi_kerja' => 'Prestasi Kerja',
            'uang_makan' => 'Uang Makan',
            'keterangan' => 'Keterangan',
        ];
    }

    public function getInfoTpp()
    {
        return $this->kode . ' - Rp ' . Yii::$app->formatter->asDecimal($this->tpp) . ' (' . $this->keterangan . ')';
    }
}
