<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prestasi_perilaku_kerja".
 *
 * @property int $id
 * @property string $NIP
 * @property string $bulan
 * @property int $hadir
 * @property int $masuk_tepat_waktu
 * @property string $masuk_terlambat
 * @property int $absen_siang
 * @property int $pulang_tepat_waktu
 * @property string $pulang_cepat
 * @property int $jumlah_hari_kerja
 * @property double $bobot_kehadiran
 * @property double $bobot_ketepatan
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
            [['bulan', 'masuk_terlambat', 'pulang_cepat'], 'safe'],
            [['hadir', 'masuk_tepat_waktu', 'absen_siang', 'pulang_tepat_waktu', 'jumlah_hari_kerja'], 'integer'],
            [['bobot_kehadiran', 'bobot_ketepatan'], 'number'],
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
            'hadir' => 'Hadir',
            'masuk_tepat_waktu' => 'Masuk Tepat Waktu',
            'masuk_terlambat' => 'Masuk Terlambat',
            'absen_siang' => 'Absen Siang',
            'pulang_tepat_waktu' => 'Pulang Tepat Waktu',
            'pulang_cepat' => 'Pulang Cepat',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'bobot_kehadiran' => 'Bobot Kehadiran',
            'bobot_ketepatan' => 'Bobot Ketepatan',
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
