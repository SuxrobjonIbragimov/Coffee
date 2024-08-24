<?php

use app\models\Categories;
use kartik\file\FileInput;
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
    </div>

    <div>
        <h3>Russian</h3>
        <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>
    </div>

    <div>
        <h3>English</h3>
        <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
    </div>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(Categories::find()->all(), 'id', 'cname_uz'),
        ['prompt' => 'Select Category']
    ) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'discount_price')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        [1 => 'Active', 0 => 'Not active'],
        ['prompt' => 'Select Status']
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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
