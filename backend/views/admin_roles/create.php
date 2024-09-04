<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AdminRoles $model */

$this->title = Yii::t('app', 'Create Admin Roles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-roles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
