<?php

namespace frontend\controllers;

use frontend\models\Product;
use frontend\models\Vieweds;
use Yii;
use yii\web\Controller;

class AboutController extends Controller
{
    public function actionIndex($id)
    {
        $currentLang = Yii::$app->session->get('lang', 'uz');
        $product = Product::findOne($id);
        $images = [];
        $category = null;
        $allCategoryProducts = [];
        $specifications = [];

        if ($product !== null) {
            $specifications = $product->specifications;
        }

        if ($product !== null) {
            $images = $product->images;
            $category = $product->category;

            if ($category !== null) {
                $allCategoryProducts = $category->products;

                foreach ($category->children as $child) {
                    $allCategoryProducts = array_merge($allCategoryProducts, $child->products);
                }
            }

            $userId = Yii::$app->user->id;
            if ($userId !== null) {
                $viewed = new Vieweds();
                $viewed->user_id = $userId;
                $viewed->product_id = $product->id;
                $viewed->viewed_at = new \yii\db\Expression('NOW()');

                if (!Vieweds::find()->where(['user_id' => $userId, 'product_id' => $product->id])->exists()) {
                    $viewed->save();
                }
            }
        }

        return $this->render('index', [
            'product' => $product,
            'images' => $images,
            'category' => $category,
            'allCategoryProducts' => $allCategoryProducts,
            'currentLang' => $currentLang,
            'specifications' => $specifications,
        ]);
    }
}