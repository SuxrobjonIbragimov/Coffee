<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="">
    <style>
        .product-detail-container {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            max-width: 800px;
            margin: 0 auto;
        }

        .product-images {
            flex: 1;
            margin-right: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .main-image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .main-image {
            max-width: 100%;
            max-height: 250px;
            border-radius: 5px;
            object-fit: cover;
        }

        .thumbnail-carousel {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumbnails {
            display: flex;
            overflow-x: auto;
            margin: 0 10px;
        }

        .thumbnail-image {
            max-width: 60px;
            max-height: 60px;
            margin-right: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            object-fit: cover;
        }

        .thumbnail-image:hover, .thumbnail-image.active {
            border-color: #007bff;
        }

        .carousel-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #333;
        }

        .product-info {
            flex: 0.5;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            max-width: 300px;
        }



        .btn-add-to-cart {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-add-to-cart:hover {
            background-color: #218838;
        }

        .comments-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 40%;
            margin-left: 30%;
        }

        .comments-section h3 {
            margin-bottom: 10px;
        }

        .comments-section p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div style="margin-top: 100px" class="product-detail-container">
    <div class="product-images">
        <!-- Main Image Display -->
        <div class="main-image-container">
            <a href="#" id="mainImageLink">
                <img id="mainImage" class="main-image" src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" alt="Main Image">
            </a>
        </div>

        <!-- Thumbnail Carousel -->
        <div class="thumbnail-carousel">
            <button class="carousel-btn prev-btn">&#10094;</button>
            <div class="thumbnails">
                <?php foreach ($product->images as $image): ?>
                    <img class="thumbnail-image" src="<?= Yii::getAlias('@web/uploads/' . $image->image_file_name) ?>" alt="Thumbnail Image">
                <?php endforeach; ?>
            </div>
            <button class="carousel-btn next-btn">&#10095;</button>
        </div>
    </div>

    <div  class="product-info">
        <h2 style="margin-top: 50px"><?= Html::encode($product->name) ?></h2>
            <h3 style="" class="price-value"><?= Html::encode($product->price) ?>$</h3>
        <?= Html::encode($product->{'description_' . $currentLang}) ?><br>

        <button class="btn-add-to-cart add-to-basket" data-product-id="<?= $product->id ?>">
<!--            <a style="color: white" href="--><?php //= Url::to(['/basket/add', 'id' => $product->id]) ?><!--" >-->
                <?=Yii::t('app','Add to cart')?>
<!--            </a>-->
        </button>
    </div>

</div>

<div class="comments-section">
    <?php if (!empty($specifications)): ?>
    <h3><?=Yii::t('app','Specifications:')?></h3>
    <ul>
        <?php foreach ($specifications as $spec): ?>
            <li>
                <strong><?= Html::encode($spec->{"key_$currentLang"}) ?>:</strong> <?= Html::encode($spec->{"value_$currentLang"}) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif;?>
</div>
<script>
    // Carousel button functionality
    document.querySelector('.prev-btn').addEventListener('click', () => {
        const thumbnails = document.querySelector('.thumbnails');
        thumbnails.scrollLeft -= 100;
    });

    document.querySelector('.next-btn').addEventListener('click', () => {
        const thumbnails = document.querySelector('.thumbnails');
        thumbnails.scrollLeft += 100;
    });

</script>
</body>
</html>
