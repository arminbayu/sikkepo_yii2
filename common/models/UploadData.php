<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "upload_data".
 *
 * @property int $id
 * @property int $terminal
 * @property string $file
 * @property string $tanggal
 * @property int $status
 */
class UploadData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['terminal', 'status'], 'integer'],
            [['tanggal'], 'safe'],
            [['file'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'terminal' => 'Terminal',
            'file' => 'File',
            'tanggal' => 'Tanggal',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitKerja()
    {
        return $this->hasOne(Terminal::className(), ['kode' => 'terminal']);
    }
}
