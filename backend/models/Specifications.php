<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "specifications".
 *
 * @property int $id
 * @property int $product_id
 * @property string $key_uz
 * @property string $value_uz
 * @property string $key_en
 * @property string $value_en
 * @property string $key_ru
 * @property string $value_ru
 *
 * @property Products $product
 */
class Specifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
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
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'key_uz' => Yii::t('app', 'Key Uz'),
            'value_uz' => Yii::t('app', 'Value Uz'),
            'key_en' => Yii::t('app', 'Key En'),
            'value_en' => Yii::t('app', 'Value En'),
            'key_ru' => Yii::t('app', 'Key Ru'),
            'value_ru' => Yii::t('app', 'Value Ru'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }
}
