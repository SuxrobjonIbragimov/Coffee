<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var app\models\OrderDetails[] $orderDetails */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'surname',
            'phone',
            'address',
            'delivery_type',
            'payment_type',
            [
                'attribute' => 'user_name',
                'value' => $model->user->username,
                'label' => 'User Name',
            ],

            [
                'label' => 'Total Price',
                'value' => Yii::$app->formatter->asCurrency(array_sum(array_map(function($item) {
                    return $item['quantity'] * $item['price'];
                }, $orderDetails))),
            ],

            ],
    ]) ?>

</div>

<div class="order-details-view">

    <h2>Order Details</h2>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $orderDetails,
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product_name',
                'value' => function ($model) {
                    return $model->product->name_uz;
                },
                'label' => 'Product Name',
            ],
            'quantity',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'unit_price',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->price);
                },
                'label' => 'Price',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'total_price',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->quantity * $model->price);
                },
            ],

            [
                'attribute' => 'product_image',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->productImage) {
                        return Html::img(Url::to('http://localhost:8881/uploads/' . $model->productImage->image_file_name), ['style' => 'width: 120px;']);
                    } else {
                        return 'No image available';
                    }
                },
                'label' => 'Product Image',
            ],

        ],
    ]); ?>

</div>
