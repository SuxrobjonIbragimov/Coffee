<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'orders';
    }

    public function rules()
    {
        return [
            [['user_id', 'name','surname','phone','address', 'delivery_type', 'payment_type'], 'required'],
            [['user_id'], 'integer'],
        ];
    }
}


