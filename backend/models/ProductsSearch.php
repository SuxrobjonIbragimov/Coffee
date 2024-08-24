<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    public $cname_uz;

    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['name_uz','name_ru','name_en','discount_price','status','cname_uz'], 'safe'],
            [['price'], 'number'],
        ];
    }

    public function search($params)
    {
        $query = Products::find();
        $query->joinWith('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

// Filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
        ]);

        $query->andFilterWhere(['ilike', 'name_uz', $this->name_uz])
            ->andFilterWhere(['ilike', 'name_en', $this->name_en])
            ->andFilterWhere(['ilike', 'name_ru', $this->name_ru])
            ->andFilterWhere(['ilike', 'categories.cname_uz', $this->cname_uz]);

        return $dataProvider;

    }
}
