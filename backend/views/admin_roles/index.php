<?php

use app\models\AdminRoles;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AdminRolesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Admin Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
//            'role_name',
            'created_at',
            [
                'label' => 'Roles',
                'value' => function ($model) {
                    $auth = Yii::$app->authManager;
                    $roles = ArrayHelper::getColumn($auth->getRolesByUser($model->user_id), 'name');
                    return implode(', ', $roles);
                },
            ],
            [
                'label' => 'Permissions',
                'value' => function ($model) {
                    $auth = Yii::$app->authManager;
                    $permissions = ArrayHelper::getColumn($auth->getPermissionsByUser($model->user_id), 'name');
                    return implode(', ', $permissions);
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}',
                'urlCreator' => function ($action, AdminRoles $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],

        ],
    ]); ?>
</div>
