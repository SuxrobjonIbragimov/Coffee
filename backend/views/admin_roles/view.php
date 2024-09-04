<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AdminRoles $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="admin-roles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php
    $auth = Yii::$app->authManager;
    $roles = ArrayHelper::getColumn($auth->getRolesByUser($model->user_id), 'name');
    $permissions = ArrayHelper::getColumn($auth->getPermissionsByUser($model->user_id), 'name');
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
//            'role_name',
            'created_at',
            [
                'label' => 'Roles',
                'value' => implode(', ', $roles),
            ],
            [
                'label' => 'Permissions',
                'value' => implode(', ', $permissions),
            ],
        ],
    ]) ?>

</div>
