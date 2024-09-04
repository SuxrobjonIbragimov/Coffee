<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public $name;

    public static function tableName(){
        return 'categories';
    }

    public function rules()
    {
        return [
            [['cname_uz', 'cname_ru', 'cname_en'], 'required'],
            [['cname_uz', 'cname_ru', 'cname_en', 'parent_id'], 'safe'],
            [['cname_uz', 'cname_ru', 'cname_en'], 'string', 'max' => 255],
        ];
    }

    public function getProduct()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
    public function getChildren()
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }


    public function afterFind()
    {
        $lang = Yii::$app->language;
        if (!in_array($lang, ['en', 'ru'])) {
            $lang = 'uz';
        }
        $this->name = $this->{'cname_' . $lang};
        parent::afterFind();
    }
}
