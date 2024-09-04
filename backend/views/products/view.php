<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var array $images */

$this->title = $model->name_uz;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_uz',
            'name_ru',
            'name_en',
            'price',
//            'discount_price',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 1 ? 'Active' : 'Inactive';
                },
            ],
            [
                'attribute' => 'category_id',
                'value' => function($model) {
                    return $model->category->cname_uz;
                },
                'label' => 'Category Name',
            ],
            [
                'attribute' => 'type',
                'value' => function($model) {
                    $types = [0 => 'Specialty', 1 => 'Usual', 2 => 'Premium'];
                    return isset($types[$model->type]) ? $types[$model->type] : 'N/A';
                },
            ],
        ],
    ]) ?>

    <div class="container">
        <div class="row">
            <?php foreach ($images as $image): ?>
                <div class="card border-success" style="width: 10rem; ">
                    <img src="<?= Url::to('http://localhost:8881/uploads/')?><?= $image->image_file_name?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
