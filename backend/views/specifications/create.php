<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Specifications $model */

$this->title = Yii::t('app', 'Create Specifications');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Specifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specifications-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
