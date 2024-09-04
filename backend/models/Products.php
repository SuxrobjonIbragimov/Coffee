<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property int $category_id
 *
 * @property Categories $category
 * @property OrderDetails[] $orderDetails
 * @property ProductImages[] $productImages
 */
class Products extends \yii\db\ActiveRecord
{
    public $imageFiles;
    public $specifications = [];

    const LANGUAGES = ['uz', 'ru', 'en'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_uz', 'name_en', 'name_ru', 'price', 'status', 'category_id', 'type','count'], 'required'],
            [['discount_price', 'status', 'price','count'], 'number'],
            [['category_id'], 'default', 'value' => null],
            [['status'], 'in', 'range' => [0, 1]],
            [['type'], 'in', 'range' => [0, 1, 2, 3]],
            [['category_id'], 'integer'],
            [['name_uz', 'name_en', 'name_ru','description_uz','description_en','description_ru'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 10],
            [['specifications'], 'safe'],
        ];
    }


    public function upload()
    {
        $folder = Yii::getAlias("@frontend/web/uploads/");
        if (!is_dir($folder)) {
            FileHelper::createDirectory($folder);
        }

        if ($this->validate()) {
            $savedFiles = [];
            foreach ($this->imageFiles as $file) {
                $filePath = $folder . $file->baseName . '.' . $file->extension;
                if ($file->saveAs($filePath)) {
                    $savedFiles[] = $file->baseName . '.' . $file->extension;
                }
            }
            return $savedFiles;
        }
        return false;
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[OrderDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetails::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImages::class, ['product_id' => 'id']);
    }

    public function getImages()
    {
        return $this->hasMany(ProductImages::className(), ['product_id' => 'id']);
    }
}

