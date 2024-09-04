<?php

use app\models\Categories;
use kartik\file\FileInput;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $initialPreview */
/** @var array $initialPreviewConfig */

?>

<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div>
        <h3>Uzbek</h3>
        <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description_uz')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    </div>

    <div>
        <h3>Russian</h3>
        <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description_ru')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    </div>

    <div>
        <h3>English</h3>
        <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description_en')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    </div>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(Categories::find()->all(), 'id', 'cname_uz'),
        ['prompt' => 'Select Category']
    ) ?>

    <?= $form->field($model, 'price')->textInput() ?>

<!--    --><?php //= $form->field($model, 'discount_price')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        [1 => 'Active', 0 => 'Not active'],
        ['prompt' => 'Select Status']
    ) ?>

    <?= $form->field($model, 'type')->dropDownList(
        [0 => 'Specialty', 1 => 'Usual', 2 => 'Premium' , 3 => 'Trending'],
        ['prompt' => 'Select Type']
    ) ?>

    <?= $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
        'options' => ['multiple' => true, 'accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => $initialPreview,
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => false,
            'showUpload' => false,
            'showRemove' => false,
            'browseOnZoneClick' => true,
            'fileActionSettings' => [
                'showRemove' => true,
                'showUpload' => false,
                'showZoom' => true,
            ],
        ],
    ]); ?>


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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
