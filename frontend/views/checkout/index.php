<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \frontend\models\OrderForm */

$this->title = 'Checkout';
$this->params['breadcrumbs'][] = $this->title;
?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/coffee/css/bootstrap.min.css') ?>">
    <style>
        .site-checkout {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #f9f9f9;
        }
        .site-checkout h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .site-checkout .form-group {
            text-align: center;
        }
    </style>
</head>

<div class="site-checkout">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to complete your order:</p>

    <?php $form = ActiveForm::begin(['id' => 'checkout-form']); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'address')->textInput() ?>

    <?= $form->field($model, 'delivery_type')->dropDownList([
        'uygacha yetqazib berish' => 'Uygacha yetqazib berish',
        'pochta orqali olib ketish' => 'Pochta orqali olib ketish',
    ],
        ['prompt' => 'Select Delivery Type'])

    ?>

    <?= $form->field($model, 'payment_type')->dropDownList([
        'Naxt' => 'Naxt',
        'Karta orqali' => 'Karta orqali',
        'Payme' => 'Payme',
        'Click' => 'Click',
    ], ['prompt' => 'Select Payment Type']) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit Order', ['class' => 'btn btn-primary', 'name' => 'checkout-button', 'id' => 'myButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = document.getElementById('checkout-form');
            var name = form.elements['OrderForm[name]'].value;
            var surname = form.elements['OrderForm[surname]'].value;
            var phone = form.elements['OrderForm[phone]'].value;
            var address = form.elements['OrderForm[address]'].value;
            var deliveryType = form.elements['OrderForm[delivery_type]'].value;
            var paymentType = form.elements['OrderForm[payment_type]'].value;

            if (name && surname && phone && address && deliveryType && paymentType) {
                Swal.fire({
                    title: "Buyurtmangiz rasmiylashtirildi!",
                    icon: "success",
                    timer: 3000,
                }).then(function() {
                    form.submit();
                });
            } else {
                Swal.fire({
                    title: "Iltimos, barcha maydonlarni to'ldiring",
                    icon: "warning",
                    timer: 3000,
                });
            }
        });
    </script>
</div>
