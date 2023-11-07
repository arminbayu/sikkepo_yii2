<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prestasi_perilaku_kerja".
 *
 * @property int $id
 * @property string $NIP
 * @property string $gol
 * @property string $unit_kerja
 * @property string $bulan
 * @property int $jumlah_hari_kerja
 * @property int $hadir
 * @property double $bobot_kehadiran
 * @property double $bobot_ketepatan
 * @property double $jumlah_bobot_ppk
 * @property int $kinerja
 * @property double $bobot_kinerja
 * @property double $jumlah_total
 * @property int $tpp
 * @property int $tpp_sebelum_pajak
 * @property int $pajak
 * @property double $pajak_tpp
 * @property int $tpp_final
 */
class PrestasiPerilakuKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'laporan_tpp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan'], 'safe'],
            [['jumlah_hari_kerja', 'hadir', 'kinerja', 'tpp', 'tpp_sebelum_pajak', 'pajak_tpp', 'tpp_final'], 'integer'],
            [['bobot_kehadiran', 'bobot_ketepatan', 'jumlah_bobot_ppk', 'bobot_kinerja', 'jumlah_total', 'pajak'], 'number'],
            [['unit_kerja'], 'string', 'max' => 2],
            [['gol'], 'string', 'max' => 8],
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
            'gol' => 'Gol',
            'unit_kerja' => 'Unit Kerja',
            'bulan' => 'Bulan',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'hadir' => 'Hadir',
            'bobot_kehadiran' => 'Bobot Kehadiran',
            'bobot_ketepatan' => 'Bobot Ketepatan',
            'jumlah_bobot_ppk' => 'Jumlah Bobot PPK',
            'kinerja' => 'Kinerja',
            'bobot_kinerja' => 'Bobot Kinerja',
            'jumlah_total' => 'Jumlah Total',
            'tpp' => 'TPP',
            'tpp_sebelum_pajak' => 'TPP Sebelum Pajak',
            'pajak' => 'Pajak',
            'pajak_tpp' => 'Pajak TPP',
            'tpp_final' => 'TPP Final',
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
