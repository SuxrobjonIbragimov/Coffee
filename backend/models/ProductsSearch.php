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
    const TYPE_MAP = [
        'specialty' => 0,
        'usual' => 1,
        'premium' => 2,
        'trending' => 3,
    ];
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name_uz','name_ru','name_en','discount_price','status','cname_uz', 'type'], 'safe'],
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

        $statusValue = null;
        if ($this->status !== null && is_string($this->status)) {
            $statusLower = strtolower($this->status);
            if ($statusLower == 'active') {
                $statusValue = 1;
            } elseif ($statusLower == 'inactive') {
                $statusValue = 0;
            }
        }

        $typeValue = null;
        if ($this->type !== null && is_string($this->type)) {
            $typeLower = strtolower($this->type);
            if (array_key_exists($typeLower, self::TYPE_MAP)) {
                $typeValue = self::TYPE_MAP[$typeLower];
            }
        }

        // Filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'status' => $statusValue,
            'type' => $typeValue,
        ]);

        $query->andFilterWhere(['ilike', 'name_uz', $this->name_uz])
            ->andFilterWhere(['ilike', 'name_en', $this->name_en])
            ->andFilterWhere(['ilike', 'name_ru', $this->name_ru])
            ->andFilterWhere(['ilike', 'categories.cname_uz', $this->cname_uz]);

        return $dataProvider;
    }
}
