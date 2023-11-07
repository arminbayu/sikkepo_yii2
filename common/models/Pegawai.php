<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_pegawai".
 *
 * @property string $NIP
 * @property string $nama
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $gol_ruang
 * @property string $tmt_pangkat
 * @property string $jabatan
 * @property string $tmt_jabatan
 * @property string $unit_kerja
 * @property string $eselon
 * @property string $pangkat_cpns
 * @property string $tmt_cpns
 * @property string $pangkat_pns
 * @property string $tmt_pns
 * @property string $gaji_pokok
 * @property string $tmt_gaji
 * @property string $tingkat_pendidikan
 * @property string $pendidikan_umum
 * @property int $kode_terminal
 * @property int $no_absen
 * @property string $kode_tpp
 *
 * @property AbsenPegawai[] $absenPegawai
 * @property DataTpp $kodeTpp
 * @property UnitKerja $unitKerja
 * @property DataAbsen $dataAbsen
 * @property UserPegawai[] $userPegawai
 */
class Pegawai extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NIP'], 'required'],
            [['tanggal_lahir'], 'safe'],
            [['kode_terminal', 'no_absen'], 'integer'],
            [['NIP'], 'string', 'max' => 18],
            [['nama', 'jabatan'], 'string', 'max' => 255],
            [['tempat_lahir', 'pangkat_cpns', 'pangkat_pns', 'tingkat_pendidikan'], 'string', 'max' => 32],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['gol_ruang', 'eselon'], 'string', 'max' => 8],
            [['tmt_pangkat', 'tmt_jabatan', 'tmt_cpns', 'tmt_pns', 'gaji_pokok', 'tmt_gaji'], 'string', 'max' => 16],
            [['unit_kerja', 'kode_tpp'], 'string', 'max' => 2],
            [['pendidikan_umum'], 'string', 'max' => 64],
            [['kode_tpp'], 'exist', 'skipOnError' => true, 'targetClass' => DataTpp::className(), 'targetAttribute' => ['kode_tpp' => 'kode']],
            [['unit_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => UnitKerja::className(), 'targetAttribute' => ['unit_kerja' => 'kode']],
            // [['kode_terminal'], 'exist', 'skipOnError' => true, 'targetClass' => DataAbsen::className(), 'targetAttribute' => ['kode_terminal' => 'kode_terminal']],
            // [['no_absen'], 'exist', 'skipOnError' => true, 'targetClass' => DataAbsen::className(), 'targetAttribute' => ['no_absen' => 'pin']],
            [['kode_terminal', 'no_absen'], 'unique', 'targetAttribute' => ['kode_terminal', 'no_absen'], 'message' => '{attribute} {value} sudah pernah digunakan.'],
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
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'gol_ruang' => 'Gol Ruang',
            'tmt_pangkat' => 'TMT Pangkat',
            'jabatan' => 'Jabatan',
            'tmt_jabatan' => 'TMT Jabatan',
            'unit_kerja' => 'Unit Kerja',
            'eselon' => 'Eselon',
            'pangkat_cpns' => 'Pangkat CPNS',
            'tmt_cpns' => 'TMT CPNS',
            'pangkat_pns' => 'Pangkat Saat Diangkat PNS',
            'tmt_pns' => 'TMT PNS',
            'gaji_pokok' => 'Gaji Pokok',
            'tmt_gaji' => 'TMT Gaji',
            'tingkat_pendidikan' => 'Tingkat Pendidikan',
            'pendidikan_umum' => 'Pendidikan Umum',
            'kode_terminal' => 'Kode Terminal',
            'no_absen' => 'No Absen',
            'kode_tpp' => 'Kode TPP',
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
    public function getKodeTpp()
    {
        return $this->hasOne(DataTpp::className(), ['kode' => 'kode_tpp']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitKerja()
    {
        return $this->hasOne(UnitKerja::className(), ['kode' => 'unit_kerja']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataAbsen()
    {
        return $this->hasOne(DataAbsen::className(), ['pin' => 'no_absen']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPegawai()
    {
        return $this->hasMany(UserPegawai::className(), ['username' => 'NIP']);
    }
}
