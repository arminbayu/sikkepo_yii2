<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hukuman_disiplin".
 *
 * @property int $id
 * @property string $NIP
 * @property string $no_sk
 * @property string $tanggal
 * @property int $jenis
 *
 * @property Pegawai $nip
 */
class HukumanDisiplin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hukuman_disiplin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal'], 'safe'],
            [['jenis'], 'integer'],
            [['NIP'], 'string', 'max' => 18],
            [['no_sk'], 'string', 'max' => 64],
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
            'no_sk' => 'No SK',
            'tanggal' => 'Tanggal',
            'jenis' => 'Jenis',
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
