<?php

use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Specifications $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="specifications-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')->dropDownList(
        ArrayHelper::map(\app\models\Products::find()->all(), 'id', 'name_uz'),
        ['prompt' => 'Select Category']
    ) ?>
    <?= $form->field($model, 'specifications')->widget(MultipleInput::class, [
        'max' => 5,
        'min' => 1,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_ROW,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
            'label' => '<i class="fa fa-plus"></i>',
        ],
        'removeButtonOptions' => [
            'class' => 'btn btn-danger',
            'label' => '<i class="fa fa-times"></i>',
        ],
        'columns' => [
            [
                'name' => 'key_uz',
                'type' => 'textInput',
                'title' => 'Key (Uz)',
                'options' => [
                    'placeholder' => 'Key (Uz)',
                ],
            ],
            [
                'name' => 'value_uz',
                'type' => 'textInput',
                'title' => 'Value (Uz)',
                'options' => [
                    'placeholder' => 'Value (Uz)',
                ],
            ],
            [
                'name' => 'key_ru',
                'type' => 'textInput',
                'title' => 'Key (Ru)',
                'options' => [
                    'placeholder' => 'Key (Ru)',
                ],
            ],
            [
                'name' => 'value_ru',
                'type' => 'textInput',
                'title' => 'Value (Ru)',
                'options' => [
                    'placeholder' => 'Value (Ru)',
                ],
            ],
            [
                'name' => 'key_en',
                'type' => 'textInput',
                'title' => 'Key (En)',
                'options' => [
                    'placeholder' => 'Key (En)',
                ],
            ],
            [
                'name' => 'value_en',
                'type' => 'textInput',
                'title' => 'Value (En)',
                'options' => [
                    'placeholder' => 'Value (En)',
                ],
            ],
        ],
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
