<?php

use app\models\Products;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProductImages $model */
/** @var yii\widgets\ActiveForm $form */
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<div class="product-images-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    $products = Products::find()->all();
    echo $form->field($model, 'product_id')->dropDownList(
        ArrayHelper::map($products, 'id', 'name'),
        ['prompt' => 'Select Product']
    );
    ?> <br>

    <div class="form-group">
        <label class="control-label" for="imageFiles">Upload Images</label>
        <div class="custom-file">
            <?= Html::activeInput('file', $model, 'imageFiles[]', ['class' => 'custom-file-input', 'multiple' => true, 'accept' => 'image/*']) ?>
            <label class="custom-file-label" for="imageFiles">Choose files</label>
        </div>
    </div>

    <div id="selected-files">
        <h3>Selected Files</h3>
        <div id="file-thumbnails"></div>
    </div><br>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(".custom-file-input").on("change", function() {
    var files = this.files;
    var fileThumbnails = $("#file-thumbnails");
    fileThumbnails.empty();
    for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = $("<img>").attr("src", e.target.result).attr("class", "img-thumbnail").attr("style", "width: 150px; margin: 5px;");
            fileThumbnails.append(img);
        }
        reader.readAsDataURL(files[i]);
    }
    var fileName = Array.from(files).map(file => file.name).join(", ");
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
JS;
$this->registerJs($js);

?>

