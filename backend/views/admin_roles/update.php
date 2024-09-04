<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AdminRoles $model */

$this->title = Yii::t('app', 'Update Admin Roles: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="admin-roles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles, // Pass roles to the _form view
        'permissions' => $permissions, // Pass permissions to the _form view
        'assignedPermissions' => $assignedPermissions, // Pass assigned permissions to the _form view
    ]) ?>

</div>

