<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Specifications;

/**
 * SpecificationsSearch represents the model behind the search form of `app\models\Specifications`.
 */
class SpecificationsSearch extends Specifications
{
    public $name_uz;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id'], 'integer'],
            [['key_uz', 'value_uz', 'key_en', 'value_en', 'key_ru', 'value_ru','name_uz'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Specifications::find();
        $query->joinWith('product');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
        ]);

        $query->andFilterWhere(['ilike', 'key_uz', $this->key_uz])
            ->andFilterWhere(['ilike', 'value_uz', $this->value_uz])
            ->andFilterWhere(['ilike', 'key_en', $this->key_en])
            ->andFilterWhere(['ilike', 'value_en', $this->value_en])
            ->andFilterWhere(['ilike', 'key_ru', $this->key_ru])
            ->andFilterWhere(['ilike', 'value_ru', $this->value_ru])
            ->andFilterWhere(['ilike', 'products.name_uz', $this->name_uz]);
        ;

        return $dataProvider;
    }
}
