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

        // Parse selected items to extract product IDs and quantities
        foreach ($selectedProductData as $item) {
            list($productId, $quantity) = explode(':', $item);
            $productIds[] = (int)$productId;
            $quantities[$productId] = (int)$quantity;
        }

        $model = new OrderForm();

        // Get user's last order for auto-filling form
        $lastOrder = Order::find()
            ->where(['user_id' => $userId])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        // Auto-fill form if a previous order exists
        if ($lastOrder) {
            $model->name = $lastOrder->name;
            $model->surname = $lastOrder->surname;
            $model->phone = $lastOrder->phone;
            $model->address = $lastOrder->address;
            $model->delivery_type = $lastOrder->delivery_type;
            $model->payment_type = $lastOrder->payment_type;
        }

        // Validate and save order form
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
                // Get selected basket items by product IDs
                $basketItems = Basket::find()
                    ->where(['user_id' => $order->user_id, 'id' => $productIds])
                    ->all();

                foreach ($basketItems as $item) {
                    // Get the corresponding quantity for each item
                    $quantity = isset($quantities[$item->id]) ? $quantities[$item->id] : $item->quantity;

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $item->product_id;
                    $orderItem->quantity = $quantity;
                    $orderItem->price = $item->product->price;

                    if ($orderItem->save()) {
                        // Reduce product stock
                        $product = Product::findOne($item->product_id);
                        if ($product) {
                            $product->count -= $quantity;
                            $product->save(false);
                        }
                    }
                }

                // Order saved, redirect user
                return $this->redirect(['free/index']);
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
