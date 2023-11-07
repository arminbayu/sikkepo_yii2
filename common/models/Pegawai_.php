<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pegawai".
 *
 * @property string $NIP
 * @property string $nama
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $jabatan
 * @property string $kode_tpp
 * @property string $eselon
 * @property string $golongan
 * @property string $kode_satker
 * @property string $TMT_pegawai
 * @property string $TMT_pensiun
 * @property string $alamat_rumah
 * @property string $wilayah
 * @property string $no_telepon
 * @property string $fasilitas
 * @property int $no_absen
 * @property int $status_peg
 *
 * @property AbsenPegawai[] $absenPegawai
 * @property DataAbsen[] $dataAbsen
 * @property HukumanDisiplin[] $hukumanDisiplin
 * @property Tpp $kodeTpp
 * @property SatuanKerja $kodeSatker
 * @property PrestasiPerilakuKerja[] $prestasiPerilakuKerja
 * @property UserPegawai $userPegawai
 */
class Pegawai extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NIP'], 'required'],
            [['tanggal_lahir', 'TMT_pegawai', 'TMT_pensiun'], 'safe'],
            [['no_absen', 'status_peg'], 'integer'],
            [['NIP'], 'string', 'max' => 18],
            [['nama', 'jabatan'], 'string', 'max' => 64],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['kode_tpp', 'kode_satker'], 'string', 'max' => 2],
            [['eselon', 'golongan'], 'string', 'max' => 8],
            [['alamat_rumah'], 'string', 'max' => 255],
            [['wilayah'], 'string', 'max' => 32],
            [['no_telepon', 'fasilitas'], 'string', 'max' => 16],
            [['no_absen'], 'unique'],
            [['kode_tpp'], 'exist', 'skipOnError' => true, 'targetClass' => Tpp::className(), 'targetAttribute' => ['kode_tpp' => 'kode']],
            [['kode_satker'], 'exist', 'skipOnError' => true, 'targetClass' => SatuanKerja::className(), 'targetAttribute' => ['kode_satker' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NIP' => 'NIP',
            'nama' => 'Nama',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'jabatan' => 'Jabatan',
            'kode_tpp' => 'Kode TPP',
            'eselon' => 'Eselon',
            'golongan' => 'Golongan',
            'kode_satker' => 'Kode SatKer',
            'TMT_pegawai' => 'TMT Pegawai',
            'TMT_pensiun' => 'TMT Pensiun',
            'alamat_rumah' => 'Alamat Rumah',
            'wilayah' => 'Wilayah',
            'no_telepon' => 'No Telepon',
            'fasilitas' => 'Fasilitas',
            'no_absen' => 'No Absen',
            'status_peg' => 'Status Pegawai',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbsenPegawai()
    {
        return $this->hasMany(AbsenPegawai::className(), ['NIP' => 'NIP']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataAbsen()
    {
        return $this->hasMany(DataAbsen::className(), ['pin' => 'no_absen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHukumanDisiplin()
    {
        return $this->hasMany(HukumanDisiplin::className(), ['NIP' => 'NIP']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeTpp()
    {
        return $this->hasOne(Tpp::className(), ['kode' => 'kode_tpp']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeSatker()
    {
        return $this->hasOne(SatuanKerja::className(), ['kode' => 'kode_satker']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestasiPerilakuKerja()
    {
        return $this->hasMany(PrestasiPerilakuKerja::className(), ['NIP' => 'NIP']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPegawai()
    {
        return $this->hasOne(UserPegawai::className(), ['username' => 'NIP']);
    }
}
