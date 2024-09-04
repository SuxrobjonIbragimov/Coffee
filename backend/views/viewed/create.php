<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Viewed $model */

$this->title = Yii::t('app', 'Create Viewed');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vieweds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
