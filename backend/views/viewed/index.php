<?php

use app\models\Viewed;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ViewedSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Vieweds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewed-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Viewed'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'product_id',
            'viewed_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Viewed $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },

            'visibleButtons' => [
                'view' => Yii::$app->user->can('viewViewed'),
                'update' => Yii::$app->user->can('updateViewed'),
                'delete' => Yii::$app->user->can('deleteViewed'),
            ],
                ]
        ],
    ]); ?>


</div>
