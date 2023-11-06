<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "perilaku_kerja".
 *
 * @property int $id
 * @property string $NIP
 * @property string $bulan
 * @property string $terlambat
 * @property string $pulang_cepat
 * @property int $tidak_hadir
 * @property int $hukuman_disiplin
 * @property double $bobot
 *
 * @property Pegawai $nip
 */
class PerilakuKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perilaku_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan'], 'safe'],
            [['tidak_hadir', 'hukuman_disiplin'], 'integer'],
            [['bobot'], 'number'],
            [['NIP'], 'string', 'max' => 18],
            [['terlambat', 'pulang_cepat'], 'string', 'max' => 16],
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
            'bulan' => 'Bulan',
            'terlambat' => 'Terlambat',
            'pulang_cepat' => 'Pulang Cepat',
            'tidak_hadir' => 'Tidak Hadir',
            'hukuman_disiplin' => 'Hukuman Disiplin',
            'bobot' => 'Bobot',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNip()
    {
        return $this->hasOne(Pegawai::className(), ['NIP' => 'NIP']);
    }
}
