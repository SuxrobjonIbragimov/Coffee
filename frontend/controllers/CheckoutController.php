<?php

namespace frontend\controllers;

use frontend\models\Basket;
use frontend\models\Order;
use frontend\models\OrderForm;
use frontend\models\OrderItem;
use frontend\models\Product;
use Yii;
use yii\web\Controller;

class CheckoutController extends Controller
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;

        // Get selected items (ID:quantity pairs)
        $selectedItems = Yii::$app->request->get('items');
        $selectedProductData = explode(',', $selectedItems);

        $productIds = [];
        $quantities = [];

        foreach ($selectedProductData as $item) {
            list($productId, $quantity) = explode(':', $item);
            $productIds[] = (int)$productId;
            $quantities[$productId] = (int)$quantity;
        }

        $model = new OrderForm();

        $lastOrder = Order::find()
            ->where(['user_id' => $userId])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($lastOrder) {
            $model->name = $lastOrder->name;
            $model->surname = $lastOrder->surname;
            $model->phone = $lastOrder->phone;
            $model->address = $lastOrder->address;
            $model->delivery_type = $lastOrder->delivery_type;
            $model->payment_type = $lastOrder->payment_type;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $order = new Order();
            $order->user_id = $userId;
            $order->name = $model->name;
            $order->surname = $model->surname;
            $order->phone = $model->phone;
            $order->address = $model->address;
            $order->delivery_type = $model->delivery_type;
            $order->payment_type = $model->payment_type;

            if ($order->save()) {
                $basketItems = Basket::find()
                    ->where(['user_id' => $order->user_id, 'id' => $productIds])
                    ->all();

                foreach ($basketItems as $item) {
                    $quantity = isset($quantities[$item->id]) ? $quantities[$item->id] : $item->quantity;

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $item->product_id;
                    $orderItem->quantity = $quantity;
                    $orderItem->price = $item->product->price;

                    if ($orderItem->save()) {
                        $product = Product::findOne($item->product_id);
                        if ($product) {
                            $product->count -= $quantity;
                            $product->save(false);
                        }
                    }
                }

                return $this->redirect(['free/index']);
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
