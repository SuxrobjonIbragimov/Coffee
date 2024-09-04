<?php

use app\models\Specifications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SpecificationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Specifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specifications-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Specifications'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'name_uz',
                'value' => 'product.name_uz',
            ],
            'key_uz',
            'value_uz',
            'key_en',
            'value_en',
            'key_ru',
            'value_ru',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Specifications $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },

            'visibleButtons' => [
                'view' => Yii::$app->user->can('viewSpecifications'),
                'update' => Yii::$app->user->can('updateSpecifications'),
                'delete' => Yii::$app->user->can('deleteSpecifications'),
            ],
                ]
        ],
    ]); ?>


</div>
