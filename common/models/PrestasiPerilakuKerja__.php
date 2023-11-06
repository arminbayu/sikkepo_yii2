<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prestasi_perilaku_kerja".
 *
 * @property int $id
 * @property string $NIP
 * @property string $bulan
 * @property int $jumlah_hari_kerja
 * @property int $hadir
 * @property double $bobot_kehadiran
 * @property double $bobot_ketepatan
 * @property double $bobot_total_ppk
 * @property double $bobot_kinerja
 * @property int $jumlah_total
 */
class PrestasiPerilakuKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prestasi_perilaku_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan'], 'safe'],
            [['jumlah_hari_kerja', 'hadir'], 'integer'],
            [['bobot_kehadiran', 'bobot_ketepatan', 'jumlah_bobot_ppk', 'bobot_kinerja', 'jumlah_total'], 'number'],
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
            'bulan' => 'Bulan',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'hadir' => 'Hadir',
            'bobot_kehadiran' => 'Bobot Kehadiran',
            'bobot_ketepatan' => 'Bobot Ketepatan',
            'jumlah_bobot_ppk' => 'Jumlah Bobot PPK',
            'bobot_kinerja' => 'Bobot Kinerja',
            'jumlah_total' => 'Jumlah Total',
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
