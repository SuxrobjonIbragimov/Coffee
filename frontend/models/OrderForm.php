<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class OrderForm extends ActiveRecord
{
    public $name;
    public $surname;
    public $phone;
    public $address;
    public $user_id;
    public $delivery_type;
    public $payment_type;

    public static function tableName()
    {
        return 'orders';
    }
    public function rules()
    {
        return [
            [['name', 'surname', 'phone', 'address', 'delivery_type', 'payment_type'], 'required'],
            [['name', 'surname', 'address'], 'string', 'max' => 255],
            ['phone', 'match', 'pattern' => '/^\+?\d{10,15}$/', 'message' => 'Please enter a valid phone number.'],
        ];
    }


}