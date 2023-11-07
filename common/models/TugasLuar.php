<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tugas_luar".
 *
 * @property int $id
 * @property string $NIP
 * @property string $no_surat
 * @property string $tanggal
 * @property string $dari_jam
 * @property string $sampai_jam
 * @property string $keterangan
 * @property string $coords
 * @property string $current_location
 * @property int $status
 * @property string $photo
 * @property string $tanggal_absen
 *
 * @property DataPegawai $nip
 */
class TugasLuar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tugas_luar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['tanggal', 'dari_jam', 'sampai_jam', 'tanggal_absen'], 'safe'],
            [['NIP'], 'string', 'max' => 18],
            [['photo'], 'string', 'max' => 32],
            [['no_surat', 'coords', 'current_location'], 'string', 'max' => 64],
            [['keterangan'], 'string', 'max' => 128],
            [['current_location'], 'string', 'max' => 255],
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
            'no_surat' => 'No Surat',
            'tanggal' => 'Tanggal',
            'dari_jam' => 'Dari Jam',
            'sampai_jam' => 'Sampai Jam',
            'keterangan' => 'Keterangan',
            'coords' => 'Koordinat',
            'current_location' => 'Lokasi Absen',
            'status' => 'Status',
            'photo' => 'Photo',
            'tanggal_absen' => 'Tanggal Absen',
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
