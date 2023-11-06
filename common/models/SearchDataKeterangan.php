<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataKeterangan;

/**
 * SearchDataKeterangan represents the model behind the search form of `common\models\DataKeterangan`.
 */
class SearchDataKeterangan extends DataKeterangan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keterangan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $mdl = DataKeterangan::find();

        // add conditions that should always apply here



        $this->load($params);


        $mdl->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $mdl;
    }
}
