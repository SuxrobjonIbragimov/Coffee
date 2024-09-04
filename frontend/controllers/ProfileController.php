<?php

namespace frontend\controllers;

use DateTime;
use frontend\models\Comments;
use frontend\models\Order;
use frontend\models\OrderItem;
use frontend\models\Product;
use frontend\models\User;
use Yii;
use yii\debug\models\search\Profile;
use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $profile = \frontend\models\Profile::findOne(['user_id' => $userId]);
        $user = User::findOne(['id' => $userId]);
        $orders = Order::findAll(['user_id' => $userId]);
        $orderIds = array_column($orders, 'id');
        $orderItems = OrderItem::find()->where(['order_id' => $orderIds])->all();
        $products = [];
        $groupedOrderItems = [];
        foreach ($orderItems as $orderItem) {
            $products[$orderItem->product_id] = Product::findOne($orderItem->product_id);
            $groupedOrderItems[$orderItem->order_id][] = $orderItem;
        }

        if ($profile === null) {
            $profile = new Profile();
        }

        if ($user === null) {
            $user = new User();
        }

        if ($profile->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            if ($profile->validate() && $user->validate()) {
                if ($profile->save() && $user->save()) {
                    Yii::$app->session->setFlash('success', 'Profil muvaffaqiyatli yangilandi.');
                } else {
                    Yii::$app->session->setFlash('error', 'Profilni yangilashda xatolik yuz berdi.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Tasdiqlashda xatolik.');
            }
        }


        // Comment qo'shish yoki yangilash qismi
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post('Comments');

            if (isset($postData['order_id']) && isset($postData['product_id'])) {
                $orderId = $postData['order_id'];
                $productId = $postData['product_id'];

                // Mavjud commentni qidirish yoki yaratish
                $comment = Comments::findOne([
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'product_id' => $productId,
                ]);

                if (!$comment) {
                    $comment = new Comments();
                    $comment->user_id = $userId;
                    $comment->order_id = $orderId;
                    $comment->product_id = $productId;
                }

                // Mavjud commentni yangilash yoki saqlash
                if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
                    Yii::$app->session->setFlash('success', 'Comment updated successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to update comment.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Missing order_id or product_id.');
            }
        }


        return $this->render('index', [
            'profile' => $profile,
            'groupedOrderItems' => $groupedOrderItems,
            'products' => $products,
            'user' => $user,
            'hasOrders' => !empty($orders),
            'commentModel' => new Comments(),  // Form uchun model
        ]);
    }
}
