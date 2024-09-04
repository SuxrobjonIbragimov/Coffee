<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Comment;

/**
 * CommentSearch represents the model behind the search form of `app\models\Comment`.
 */
class CommentSearch extends Comment
{
    public $name_uz;
    public $username; // username maydoni qo'shildi


    public function rules()
    {
        return [
            [['id', 'order_id', 'product_id', 'user_id'], 'integer'],
            [['comment_text', 'name_uz','username'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Comment::find();
        $query->joinWith('product');
        $query->joinWith(['user']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['username'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // validate bajarilmasa, hech qanday yozuvlarni qaytarmaydi
            $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['ilike', 'comment_text', $this->comment_text])
            ->andFilterWhere(['ilike', 'products.name_uz', $this->name_uz])
           ->andFilterWhere(['ilike', 'user.username', $this->username]);

        return $dataProvider;
    }
}
