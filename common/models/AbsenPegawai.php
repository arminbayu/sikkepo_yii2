<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "absen_pegawai".
 *
 * @property int $id
 * @property string $NIP
 * @property string $tanggal
 * @property string $jam_masuk
 * @property string $jam_siang
 * @property string $jam_keluar
 * @property string $status_masuk
 * @property string $status_siang
 * @property string $status_keluar
 * @property string $selisih_jam_masuk
 * @property string $selisih_jam_keluar
 * @property string $status
 * @property string $keterangan
 * @property string $last_updated
 *
 * @property DataPegawai $nip
 */
class AbsenPegawai extends \yii\db\ActiveRecord
{
    public $hadir, $tidak_hadir, $tidak_absen_3x, $datang_tepat_waktu, $datang_terlambat, $pulang_cepat, $tidak_absen_masuk, $tidak_absen_siang, $tidak_absen_pulang, $hari_kerja;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'absen_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['tanggal', 'jam_masuk', 'jam_siang', 'jam_keluar', 'selisih_jam_masuk', 'selisih_jam_keluar', 'last_updated'], 'safe'],
            [['NIP'], 'string', 'max' => 18],
            [['status_masuk', 'status_siang', 'status_keluar'], 'string', 'max' => 8],
            [['status', 'keterangan'], 'string', 'max' => 64],
            [['NIP'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['NIP' => 'NIP']],
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
            'jam_masuk' => 'Jam Masuk',
            'jam_siang' => 'Jam Siang',
            'jam_keluar' => 'Jam Keluar',
            'status_masuk' => 'Status Masuk',
            'status_siang' => 'Status Siang',
            'status_keluar' => 'Status Keluar',
            'selisih_jam_masuk' => 'Selisih Jam Masuk',
            'selisih_jam_keluar' => 'Selisih Jam Keluar',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'last_updated' => 'Last Updated',
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
