<?php

namespace frontend\controllers;

use frontend\models\Basket;
use frontend\models\Product;
use Yii;
use yii\web\Controller;

class BasketController extends Controller
{
    public function actionAdd($id)
    {
        if (!Yii::$app->request->isPost) {
            return ['status' => 'error', 'message' => 'Notog‘ri so‘rov turi'];
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userId = Yii::$app->user->id;
        $product = Product::findOne($id);

        if (!$product) {
            return ['status' => 'error', 'message' => 'Mahsulot topilmadi'];
        }

        $basket = Basket::findOne(['product_id' => $id, 'user_id' => $userId]);
        if ($basket) {
            $basket->quantity += 1;
        } else {
            $basket = new Basket();
            $basket->product_id = $id;
            $basket->user_id = $userId;
            $basket->quantity = 1;
            $basket->created_at = date('Y-m-d H:i:s');
        }

        if ($basket->save()) {
            return ['status' => 'success', 'message' => 'Mahsulot savatga qo\'shildi'];
        } else {
            return ['status' => 'error', 'message' => 'Savatga qo‘shishda xatolik yuz berdi'];
        }
    }


    public function actionIndex() {
        $userId = Yii::$app->user->id;

        // Foydalanuvchining savatdagi mahsulotlarini olish
        $basketItems = Basket::find()
            ->where(['user_id' => $userId])
            ->with('product')
            ->all();

        // Mahsulotning statusi yoki zaxirasiga qarab filtrni qo'llash
        $basketItems = array_filter($basketItems, function($item) {
            return $item->product !== null;
        });

        return $this->render('index', ['basketItems' => $basketItems]);
    }


    public function actionDelete($id)
    {
        $basketItem = Basket::findOne($id);
        if ($basketItem) {
            $basketItem->delete();
        }
        return $this->redirect(['basket/index']);
    }
}