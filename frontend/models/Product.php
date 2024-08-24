<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'products';
    }

    public function getImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }
    public function getCategories()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}