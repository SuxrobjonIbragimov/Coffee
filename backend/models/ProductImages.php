<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "product_images".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_file_name
 *
 * @property Products $product
 */
class ProductImages extends \yii\db\ActiveRecord
{
    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['image_file_name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 10],
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

    /**
     * Handles file upload and returns saved file names.
     *
     * @return array|bool
     */
    public function upload()
    {
        $folder = Yii::getAlias("@frontend/web/uploads/");

        if (!is_dir($folder)) {
            FileHelper::createDirectory($folder);
        }

        if ($this->validate()) {
            $savedFiles = [];
            foreach ($this->imageFiles as $file) {
                // Use the original filename instead of generating a unique one
                $filePath = $folder . $file->baseName . '.' . $file->extension;

                if ($file->saveAs($filePath)) {
                    $savedFiles[] = $file->baseName . '.' . $file->extension;
                }
            }
            return $savedFiles;
        }
        return false;
    }

}
