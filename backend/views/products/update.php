<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var array $productImages */

$this->title = Yii::t('app', 'Update Products: {name}', [
    'name' => $model->name_uz,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_uz, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview, // Pass the initial preview to the form
        'initialPreviewConfig' => $initialPreviewConfig, // Pass the initial preview config to the form
    ]) ?>

</div>


