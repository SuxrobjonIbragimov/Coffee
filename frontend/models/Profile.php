<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Profile extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name', 'surname', 'phone', 'address'], 'string', 'max' => 255],

        ];
    }

    public function getOrderDetails()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'order_id']);
    }

}