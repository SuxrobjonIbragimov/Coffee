<?php

use frontend\models\Comments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$currentLang = Yii::$app->session->get('lang', 'uz');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .containers {
            display: flex;
            justify-content: center;
            gap: 3em;
            margin: 50px 150px;
        }

        .about {
            flex-grow: 0.3;
        }

        .about a {
            background-color: #007bff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            color: white;
            display: block;
            margin-bottom: 12px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .about a.active {
            background-color: #6f42c1;
            color: white;
        }

        .about a:hover {
            background-color: #0056b3;
        }

        #profile-container, #orders-container {
            display: none;
        }

        .profile-container.active, .orders-container.active {
            display: block;
        }

        .profile-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-container form > div {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        .profile-container label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #495057;
        }

        .profile-container input, .profile-container select {
            margin-bottom: 12px;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
        }

        .profile-container button {
            background-color: #6f42c1;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .profile-container button:hover {
            background-color: #563d7c;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 600px;
        }

        .empty-state h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #6f42c1;
        }

        .empty-state p {
            margin-bottom: 20px;
            color: #777;
            font-size: 18px;
        }

        .btn-purple {
            background-color: #6f42c1;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-purple:hover {
            background-color: #4b3bbd;
        }

        .empty-state a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            margin-top: 15px;
        }

        .empty-state a:hover {
            text-decoration: underline;
        }

        .content-wrapper {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #6f42c1;
            color: white;
            border-bottom: none;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-header h5 {
            margin: 0;
            font-size: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .card-body p {
            margin: 10px 0;
            font-size: 18px;
            color: #495057;
        }

        .card-body hr {
            margin: 15px 0;
        }

        .image-link img {
            max-width: 100%;
            border-radius: 8px;
            object-fit: cover;
        }

        .col-2 img {
            height: 9rem;
            width: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .col-10 p {
            margin-bottom: 12px;
            font-size: 18px;
        }

        .nav-pills .nav-link {
            color: #6f42c1;
            font-size: 18px;
            border-radius: 8px;
            padding: 10px 20px;
            margin-left: 15px;
        }

        .nav-pills .nav-link.active {
            background-color: #6f42c1;
            color: white;
        }

        .comment-section {
            background-color: #f8f9fa; /* Engil kulrang fon */
            border-radius: 8px; /* Silliq burchaklar */
            padding: 15px; /* Ichki bo'shliq */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Engil soyalar */
            margin-top: 20px;
        }

        .comment-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #343a40; /* To'q kulrang */
        }

        .comment-textarea {
            border-radius: 6px; /* Silliq burchaklar */
            border: 1px solid #ced4da; /* Chiroyli hoshiyalar */
            padding: 10px; /* Ichki bo'shliq */
            resize: none; /* O'lchamini o'zgartira olmaslik */
            transition: border-color 0.3s ease; /* Chiroyli o'tish effekti */
        }

        .comment-textarea:focus {
            border-color: #80bdff; /* Fokusta hoshiyani rangini o'zgartirish */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Engil soyalar */
        }

        .comment-submit {
            background-color: #007bff; /* Moviy tugma */
            border-color: #007bff;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .comment-submit:hover {
            background-color: #0056b3; /* Hover effekti */
            border-color: #0056b3;
        }

    </style>
</head>
<body>
<div class="containers" id="target-section">
    <div class="about">
        <h1><?= Html::encode($profile->name) ?>&nbsp;<?= Html::encode($profile->surname) ?></h1>
        <a href="#" id="orders-link" onclick="setActiveTab(event, 'orders-container');"><?= Yii::t('app', 'My orders') ?></a>
        <a href="#" id="info-link" onclick="setActiveTab(event, 'profile-container');"><?= Yii::t('app', 'My information') ?></a>
    </div>
    <div id="orders-container" class="private orders-container" style="display: block;">
        <ul class="nav nav-pills mb-3">
            <li class="nav-item">
                <a style="margin-left: 23px;" class="nav-link active" href="#" onclick="setActiveSubTab(event, 'all-orders');"><?= Yii::t('app', 'All orders') ?></a>
            </li>
<!--            <li class="nav-item">-->
<!--                <a class="nav-link" href="#" onclick="setActiveSubTab(event, 'active-orders');">--><?php //= Yii::t('app', 'Active') ?><!--</a>-->
<!--            </li>-->
        </ul>

        <div id="all-orders" class="content-wrapper" style="display: block;">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-9">
                        <?php
                        $groupedOrderItems = array_reverse($groupedOrderItems, true);
                        foreach ($groupedOrderItems as $orderId => $orderItems): ?>
                            <div style="width: 870px" class="card mb-3">
                                <div class="card-header">
                                    <h5><?= Yii::t('app', 'Order ID number') ?> <?= Html::encode($orderId) ?></h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $order = $orderItems[0]->order;
                                    $totalOrderPrice = 0;
                                    foreach ($orderItems as $orderItem) {
                                        $totalOrderPrice += $orderItem->quantity * $orderItem->price;
                                    }
                                    ?>
                                    <p><strong><?= Yii::t('app', 'Delivery point:') ?></strong> <?= Html::encode($order->address) ?></p>
                                    <p><strong><?= Yii::t('app', 'Working hours:') ?></strong> 10:00-20:00</p>
                                    <p><strong><?= Yii::t('app', 'Order value:') ?></strong> <?= Html::encode($totalOrderPrice) ?> $</p>
                                    <hr>
                                    <div>
                                        <?= count($orderItems) ?> <?= Yii::t('app', 'product') ?>
                                    </div>

                                    <div style="margin-top: 10px">
                                        <?php foreach ($orderItems as $orderItem): ?>
                                            <?php $product = $products[$orderItem->product_id] ?? null; ?>
                                            <div class="product-row" style="display: flex; align-items: center; margin-bottom: 10px;">
                                                <div class="product-image" style="flex: 0 0 150px;">
                                                    <a href="<?= Url::to(['about/index', 'id' => $product->id]) ?>" class="image-link">
                                                        <img class="card-img img-fluid" style="height: 9rem; width: 150px;" src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" alt="Image">
                                                    </a>
                                                </div>
                                                <div class="product-details" style="flex-grow: 1; padding-left: 15px;">
                                                    <p><strong><?= Yii::t('app', 'Name:') ?></strong>
                                                        <?php
                                                        if ($currentLang == 'ru') {
                                                            echo $product->name_ru;
                                                        } elseif ($currentLang == 'en') {
                                                            echo $product->name_en;
                                                        } else {
                                                            echo $product->name_uz;
                                                        }
                                                        ?>
                                                    </p>
                                                    <p><strong><?= Yii::t('app', 'Color:') ?></strong> <?= Yii::t('app', 'White') ?></p>
                                                    <p><strong><?= Yii::t('app', 'Number:') ?></strong> <?= Html::encode($orderItem->quantity) ?></p>
                                                    <p><strong><?= Yii::t('app', 'Cost:') ?></strong> <?= Html::encode($orderItem->price) ?> $</p>
                                                </div>
                                                <?php
                                                // Commentni qidirish
                                                $comment = Comments::findOne([
                                                    'user_id' => Yii::$app->user->id,
                                                    'order_id' => $orderItem->order_id,
                                                    'product_id' => $orderItem->product_id,
                                                ]);
                                                ?>
                                                <div class="comment-section" style="flex-grow: 1; padding-left: 15px;">
                                                    <h6 class="comment-title"><?= Yii::t('app', $comment ? 'Update Comment' : 'Add a Comment') ?></h6>

                                                    <?php $form = ActiveForm::begin([
                                                        'action' => ['profile/index'],
                                                        'method' => 'post',
                                                        'options' => ['class' => 'comment-form'],
                                                    ]); ?>

                                                    <div class="form-group">
                                                        <?= $form->field($commentModel, 'comment_text')->textarea([
                                                            'rows' => 3,
                                                            'class' => 'form-control comment-textarea',
                                                            'value' => $comment ? $comment->comment_text : '',
                                                        ])->label(false) ?>
                                                    </div>

                                                    <?= $form->field($commentModel, 'order_id')->hiddenInput(['value' => $orderItem->order_id])->label(false) ?>
                                                    <?= $form->field($commentModel, 'product_id')->hiddenInput(['value' => $orderItem->product_id])->label(false) ?>

                                                    <div class="form-group text-right">
                                                        <?= Html::submitButton(Yii::t('app', $comment ? 'Update' : 'Submit'), ['class' => 'btn btn-primary btn-sm comment-submit']) ?>
                                                    </div>

                                                    <?php ActiveForm::end(); ?>
                                                </div>

                                            </div>
                                            <hr>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="active-orders" class="content-wrapper" style="display: none;">
            <?php if (empty($newOrderItems)): ?>
                <div class="empty-state">
                    <h1><?= Yii::t('app', 'There is nothing') ?></h1>
                    <p><?= Yii::t('app', 'You do not have an active order! Use the search to find everything you need!') ?></p>
                    <a href="http://localhost:8881/"><button class="btn btn-purple"><?= Yii::t('app', 'Start shopping') ?></button></a>
                    <br>
                    <a href="http://localhost:8881/"><?= Yii::t('app', 'Return to home page') ?></a>
                </div>
            <?php else: ?>
                <!-- Show active orders here -->
            <?php endif; ?>
        </div>

    </div>
    <div id="profile-container" class="profile-container" style="display: none;">
        <h1><?= Yii::t('app', 'My information') ?></h1>
        <?php $form = ActiveForm::begin(['id' => 'profile-form']); ?>
        <?= $form->field($profile, 'surname')->textInput(['maxlength' => true, 'required' => true]) ?>
        <?= $form->field($profile, 'name')->textInput(['maxlength' => true, 'required' => true]) ?>
        <?= $form->field($profile, 'phone')->textInput(['maxlength' => true, 'required' => true]) ?>
        <?= $form->field($profile, 'address')->textInput(['maxlength' => true, 'required' => true]) ?>
        <?= $form->field($user, 'email')->textInput(['maxlength' => true, 'required' => true]) ?>

        <div style="flex-basis: 100%; display: flex; justify-content: center;">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div style="margin-top: 20px;">
            <?= Html::beginForm(['/site/logout'], 'post') ?>
            <?= Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>

</div>


<!-- JavaScript code -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var savedSlideIndex = sessionStorage.getItem('activeSlideIndex');
        if (savedSlideIndex !== null) {
            $('#carouselExampleIndicators').carousel(parseInt(savedSlideIndex));
        }

        $('.carousel-link').on('click', function () {
            var slideIndex = $(this).data('slide-index');
            sessionStorage.setItem('activeSlideIndex', slideIndex);
        });

        let mybutton = document.getElementById("scrollToTopBtn");

        window.onscroll = function () {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        mybutton.onclick = function () {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        };

        let itemsToShow = 12;
        let itemsCount = $('.category-item').length;

        $('#load-more').click(function () {
            itemsToShow += 6;
            $('.category-item').slice(0, itemsToShow).fadeIn();
            if (itemsToShow >= itemsCount) {
                $(this).fadeOut();
            }
        });

        var newOrderElements = document.querySelectorAll('#active-orders .card');
        if (newOrderElements.length > 0) {
            setTimeout(function () {
                setActiveSubTab({
                    preventDefault: () => {
                    }
                }, 'all-orders');
            }, 10 * 60 * 1000); // 10 minutes in milliseconds
        }
    });

    function setActiveTab(event, containerId) {
        event.preventDefault();

        document.getElementById('orders-container').style.display = 'none';
        document.getElementById('profile-container').style.display = 'none';

        if (containerId === 'orders-container') {
            document.getElementById('orders-container').style.display = 'block';
            setActiveSubTab(event, 'all-orders');
        } else {
            document.getElementById('profile-container').style.display = 'block';
        }

        var links = document.querySelectorAll('.about a');
        links.forEach(function (link) {
            link.classList.remove('active');
        });

        event.target.classList.add('active');
    }

    function setActiveSubTab(event, subTabId) {
        event.preventDefault();

        document.getElementById('all-orders').style.display = 'none';
        document.getElementById('active-orders').style.display = 'none';

        if (subTabId === 'all-orders') {
            document.getElementById('all-orders').style.display = 'block';
        } else {
            document.getElementById('active-orders').style.display = 'block';
        }

        var subLinks = document.querySelectorAll('.nav-link');
        subLinks.forEach(function (link) {
            link.classList.remove('active');
        });

        event.target.classList.add('active');
    }
</script>
</body>
</html>

