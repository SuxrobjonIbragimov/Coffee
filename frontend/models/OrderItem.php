<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class OrderItem extends ActiveRecord
{

    public static function tableName()
    {
        return 'order_details';
    }

    public function rules()
    {
        return [
            [['order_id','product_id', 'quantity', 'price'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}
