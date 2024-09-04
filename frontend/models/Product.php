<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public $name;

    public static function tableName()
    {
        return 'products';
    }

    public function rules()
    {
        return [
            [['name_uz', 'name_en', 'name_ru', 'price','status', 'category_id','type'], 'required'],
            [['discount_price','status','price','count'], 'number'],
            [['category_id'], 'default', 'value' => null],
            [['status'], 'in', 'range' => [0, 1]],
            [['type'], 'in', 'range' => [0, 1, 2, 3]],
            [['category_id'], 'integer'],
            [['name_uz', 'name_en', 'name_ru','description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function getImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }
    public function getCategories()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getSpecifications()
    {
        return $this->hasMany(Specification::class, ['product_id' => 'id']);
    }


    public function afterFind()
    {
        $lang = Yii::$app->language;
        if (!in_array($lang, ['en', 'ru'])) {
            $lang = 'uz';
        }
        $this->name = $this->{'name_' . $lang};
        parent::afterFind();
    }
}