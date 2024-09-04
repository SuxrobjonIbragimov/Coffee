<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SpecificationsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="specifications-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'key_uz') ?>

    <?= $form->field($model, 'value_uz') ?>

    <?= $form->field($model, 'key_en') ?>

    <?php // echo $form->field($model, 'value_en') ?>

    <?php // echo $form->field($model, 'key_ru') ?>

    <?php // echo $form->field($model, 'value_ru') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
