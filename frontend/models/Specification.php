<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Specification extends ActiveRecord
{
    public static function tableName()
    {
        return 'specifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'key_uz', 'value_uz', 'key_en', 'value_en', 'key_ru', 'value_ru'], 'required'],
            [['product_id'], 'default', 'value' => null],
            [['product_id'], 'integer'],
            [['key_uz', 'value_uz', 'key_en', 'value_en', 'key_ru', 'value_ru'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}