<?php

use frontend\models\Basket;
use frontend\models\Comments;
use frontend\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;

\frontend\assets\CoffeeAsset::register($this);
/* @var $this yii\web\View */
/* @var $products \frontend\models\Product[] */
//$basketItems = Basket::find()->where(['user_id' => $userId])->with('product')->all();

$products = Product::find()
    ->with('images', 'categories')
    ->where(['type' => 1])
    ->andWhere(['!=', 'status', 0])
    ->andWhere(['>', 'count', 0])
    ->all();

$specialtyProducts = Product::find()
    ->with('images', 'categories')
    ->where(['type' => 0])
    ->andWhere(['!=', 'status', 0])
    ->andWhere(['>', 'count', 0])
    ->all();

$premiumProducts = Product::find()
    ->with('images')
    ->where(['type' => 2])
    ->andWhere(['!=', 'status', 0])
    ->andWhere(['>', 'count', 0])
    ->all();

$blogProducts = Product::find()
    ->with('images')
    ->where(['type' => 3])
    ->andWhere(['!=', 'status', 0])
    ->andWhere(['>', 'count', 0])
    ->all();


$viewedCounts = (new \yii\db\Query())
    ->select(['product_id', 'COUNT(*) AS count'])
    ->from('viewed')
    ->where(['product_id' => array_column($blogProducts, 'id')])
    ->groupBy('product_id')
    ->indexBy('product_id') // Index by product_id for easier access
    ->all();


$groupedProducts = [];
foreach ($products as $product) {
    $categoryName = $product->categories ? $product->categories->name : 'Uncategorized';
    $groupedProducts[$categoryName][] = $product;
}

$currentLang = Yii::$app->session->get('lang', 'uz');

$commentsCount = Comments::find()->count();
$productsCount = Product::find()->count();
$viewedCount = \frontend\models\Vieweds::find()->count();



?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= $currentLang ?>">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--=============== DESCRIPTION ===============-->
        <meta name="description" content="by Omonjon Sobirov">

        <link rel="stylesheet" href="<?= Yii::getAlias('@web/coffee/css/styles.css') ?>">

        <!--=============== FAVICON ===============-->
        <link rel="shortcut icon" href="<?= Yii::getAlias('@web/coffee/img/favicon.png')?>" type="image/x-icon">

        <!--        =============== BOXICONS ===============-->
        <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Jost:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <title>Coffee</title>
        <style>
            /* Ensure products are not hidden by default */
            .hidden {
                display: none;
            }


        </style>
    </head>

    <body>
    <!--==================== LOADER ====================-->
    <!--        <div class="load" id="load">-->
    <!--            <img src="--><?php //= Yii::getAlias('@web/coffee/img/loadcoffee.gif')?><!--" alt="Load Gif" class="load__gif">-->
    <!--        </div>-->

    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="http://localhost:8881/" class="nav__logo">
                <img src="<?= Yii::getAlias('@web/coffee/img/logo.png')?>" alt="Logo" class="nav__logo-img">
                Coffee
            </a>

            <div style="margin-left: 250px; margin-top: 12px" class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link"><?=Yii::t('app','Home')?></a>
                    </li>
                    <li class="nav__item">
                        <a href="#products" class="nav__link"><?=Yii::t('app','Products')?></a>
                    </li>
                    <li class="nav__item">
                        <a href="#premium" class="nav__link"><?=Yii::t('app','Premium')?></a>
                    </li>
                    <li class="nav__item">
                        <a href="#blog" class="nav__link"><?=Yii::t('app','Blog')?></a>
                    </li>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a style="color: var(--text-color)" href="<?= Url::to(['/site/login']) ?>">
                            <i style="font-size: 23px" class="fa-solid fa-user"></i>
                        </a>
                        <?php else: ?>
                        <a style="color: var(--text-color)" href="<?= Url::to(['/profile/index', 'scroll' => 1]) ?>">
                            <i style="font-size: 23px" class="fa-solid fa-user"></i>
                        </a>

                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <button type="submit" style="background:none;border:none;color: var(--text-color);">
                            <i style="font-size: 23px" class="fa-solid fa-sign-out"></i>
                        </button>
                        <?= Html::endForm() ?>
                    <?php endif; ?>
                    <a href="<?= Url::to(['basket/index', 'scroll' => 1]) ?>">
                        <i style="color: var(--text-color); font-size: 25px" class="fa-solid fa-bag-shopping">&nbsp;</i>
                    </a>&nbsp;

                    <div class="lang">
                        <form action="/site/changelang" method="get" id="form-uz">
                            <input type="hidden" name="lang" value="uz">
                        </form>
                        <form action="/site/changelang" method="get" id="form-en">
                            <input type="hidden" name="lang" value="en">
                        </form>
                        <form action="/site/changelang" method="get" id="form-ru">
                            <input type="hidden" name="lang" value="ru">
                        </form>
                        <select style="width: 100px; border-radius: 15px; height: 2rem; text-align: center" class="rounded-pill py-2 px-4 ms-5 d-none d-lg-block form-select" onchange="document.getElementById('form-' + this.value).submit();">
                            <option value="uz" <?= $currentLang == 'uz' ? 'selected' : '' ?>> 🇺🇿 Uz</option>
                            <option value="en" <?= $currentLang == 'en' ? 'selected' : '' ?>> 🇬🇧 En</option>
                            <option value="ru" <?= $currentLang == 'ru' ? 'selected' : '' ?>> 🇷🇺 Ru</option>
                        </select>
                    </div>


                </ul>
                <div class="nav__close" id="nav-close">
                    <i class="bx bx-x"></i>
                </div>
            </div>
            <!-- Toggle button -->
            <div class="nav__toggle" id="nav-toggle">
                <i class="bx bx-grid-alt"></i>
            </div>
        </nav>
    </header>

    <!--==================== MAIN ====================-->
    <main>
        <!--==================== HOME ====================-->
        <section class="home grid" id="home">
            <div class="home__container">
                <div class="home__content container">
                    <h1 class="home__title">
                       <?=Yii::t('app','Choose Your Favorite Coffee And Enjoy')?> <span></span>
                    </h1>
                    <p class="home__description">
                        <?=Yii::t('app','Buy the best and delicious coffees.')?>
                    </p>

                    <div class="home__data">
                        <div class="home__data-group">
                            <h2 class="home__data-number"><?= Yii::$app->formatter->asDecimal($commentsCount) ?> </h2>
                            <h3 class="home__data-title"><?=Yii::t('app','Testimonials')?></h3>
                            <p class="home__data-description">
                                <?=Yii::t('app','Testimonials from various customers who trust us.')?>
                            </p>
                        </div>


                        <div class="home__data-group">
                            <h2 class="home__data-number"><?= Yii::$app->formatter->asDecimal($productsCount) ?> </h2>
                            <h3 class="home__data-title"><?=Yii::t('app','Exclusive Product')?></h3>
                            <p class="home__data-description">
                                <?=Yii::t('app','Premium preparation with quality ingredients.')?>
                            </p>
                        </div>
                    </div>

                    <a href="#specialty">
                        <img src="<?= Yii::getAlias('@web/coffee/img/scroll.png') ?>" alt="Scroll" class="home__scroll">
                    </a>

                </div>
            </div>

            <img src="<?= Yii::getAlias('@web/coffee/img/home.png')?>" alt="Home" class="home__img">
        </section>
      <?= $content?>
        <!--==================== ESPECIALTY ====================-->

        <div class="specialty section container" id="specialty">
            <div class="specialty__container">
                <div class="specialty__box">
                    <h2 class="section__title">
                        <?= Yii::t('app', 'Specialty coffees that make you happy and cheer you up!') ?>
                    </h2>

                    <div class="">
                        <a href="#!" class="button specialty__button" id="seeMoreButton"><?= Yii::t('app', 'See more') ?></a>
                    </div>
                </div>

                <div class="specialty__category" id="specialtyCategory">
                    <?php foreach ($specialtyProducts as $index => $product): ?>
                        <div class="specialty__group specialty__line <?= $index >= 3 ? 'hidden' : '' ?>">
                            <a href="<?= Url::to(['/about/index', 'id' => $product->id, 'scroll' => 1]) ?>" class="quality__button">
                                <?php if (!empty($product->images)): ?>
                                    <img src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" alt="<?= Html::encode($product->{'name_' . $currentLang}) ?>" class="specialty__img">
                                <?php endif; ?>
                            </a>

                            <h3 class="specialty__title"><?= Html::encode($product->{'name_' . $currentLang}) ?></h3>
                            <p class="specialty__description">
                                <?= Html::encode($product->{'description_' . $currentLang}) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!--==================== PRODUCTS ====================-->
        <section class="products section" id="products">
            <div class="products__container container">
                <h2 class="section__title">
                    <?=Yii::t('app','Choose our delicious and best products')?>
                </h2>

                <div class="products__filter-container">
                    <ul class="products__filters">
                        <?php
                        $i = 0;
                        foreach ($groupedProducts as $categoryName => $productsInCategory):
                            $hiddenClass = ($i >= 3) ? 'hidden-category' : '';
                            ?>
                            <li class="products__item products__line <?= $hiddenClass ?>" data-filter=".<?= strtolower(Html::encode($categoryName)) ?>">
                                <h3 class="products__title"><?= Html::encode($categoryName) ?></h3>
                                <span class="products__stock"><?= count($productsInCategory) ?> <?=Yii::t('app','products')?></span>
                            </li>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </ul>
                    <div class="products__arrow">
                        <button id="toggleCategories" class="arrow-btn">
                            <i class="bx bx-down-arrow-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="products__content grid">
                    <?php foreach ($groupedProducts as $categoryName => $productsInCategory): ?>
                        <!--==================== <?= Html::encode($categoryName) ?> ====================-->
                        <?php foreach ($productsInCategory as $product): ?>
                            <article class="products__card <?= strtolower(Html::encode($categoryName)) ?>">
                                <div class="products__shape">
                                    <?php if (!empty($product->images)): ?>
                                        <a href="<?= Url::to(['/about/index', 'id' => $product->id, 'scroll' => 1]) ?>">
                                            <img style="height: 13rem" src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" class="products__img" alt="<?= Html::encode($product->name) ?>">
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="products__data">
                                    <h2 class="products__price">$<?= Html::encode($product->price) ?></h2>
                                    <h3 class="products__name"><?= Html::encode($product->name) ?></h3>

                                    <button class="button products__button add-to-basket" data-product-id="<?= $product->id ?>">
                                        <i style="color: white" class="bx bx-shopping-bag"></i>
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!--==================== QUALITY ====================-->
        <section class="quality section" id="premium">
            <div class="quality__container container">
                <h2 class="section__title">
                    <?= Yii::t('app', 'We offer a premium and better quality preparation just for you!') ?>
                </h2>

                <div class="quality__content grid">
                    <?php foreach ($premiumProducts as $index => $product): ?>
                        <div class="quality__images" data-index="<?= $index ?>" style="<?= $index > 0 ? 'display:none;' : '' ?>">
                            <a href="<?= Url::to(['/about/index', 'id' => $product->id, 'scroll' => 1]) ?>">
                                <?php if (!empty($product->images)): ?>
                                    <img src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" alt="<?= Html::encode($product->name) ?>" class="quality__img-big">
                                    <img src="<?= Yii::getAlias('@web/coffee/img/quality2.png') ?>" alt="<?= Html::encode($product->name) ?>" class="quality__img-small">
                                <?php endif; ?>
                            </a>
                        </div>

                        <div class="quality__data" data-index="<?= $index ?>" style="<?= $index > 0 ? 'display:none;' : '' ?>">
                            <h1 class="quality__title"><?= Html::encode($product->name) ?></h1>
                            <h2 class="quality__price">$<?= Html::encode($product->price) ?></h2>
                            <span class="quality__special">Special Price</span>
                            <p class="quality__description">
                                <?= Html::encode($product->{'description_' . $currentLang}) ?>
                            </p>

                            <div class="quality__buttons">
                                <a>
                                    <button class="button add-to-basket" data-product-id="<?= $product->id ?>">
                                        <?= Yii::t('app', 'Buy Now') ?>
                                    </button>
                                </a>

                                <?php if ($index === 0): ?>
                                    <a class="quality__button" id="see-more">
                                        <?= Yii::t('app', 'See more') ?>
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!--==================== LOGOS ====================-->
        <section class="logo section">
            <div class="logo__container container grid">
                <img src="<?= Yii::getAlias('@web/coffee/img/logocoffee1.png')?>" alt="Logo Coffee" class="logo__img">
                <img src="<?= Yii::getAlias('@web/coffee/img/logocoffee2.png')?>" alt="Logo Coffee" class="logo__img">
                <img src="<?= Yii::getAlias('@web/coffee/img/logocoffee3.png')?>" alt="Logo Coffee" class="logo__img">
                <img src="<?= Yii::getAlias('@web/coffee/img/logocoffee4.png')?>" alt="Logo Coffee" class="logo__img">
                <img src="<?= Yii::getAlias('@web/coffee/img/logocoffee5.png')?>" alt="Logo Coffee" class="logo__img">
            </div>
        </section>

        <!--==================== BLOG ====================-->
        <section class="blog section" id="blog" >
            <div class="blog__container container">
                <h2 class="section__title">
                    <?=Yii::t('app','Our Blogs Coffee with trending topic for this week')?>
                </h2>

                <div class="blog__content grid">
                    <?php foreach ($blogProducts as $product): ?>
                        <article class="blog__card">
                            <div class="blog__image">
                                <?php if (!empty($product->images)): ?>
                                    <img src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" alt="<?= Html::encode($product->name) ?>" class="blog__img">
                                <?php endif; ?>
                                <a href="<?= Url::to(['/about/index', 'id' => $product->id, 'scroll' => 1]) ?>" class="blog__button">
                                    <i class="bx bx-right-arrow-alt"></i>
                                </a>
                            </div>

                            <div class="blog__data">
                                <h2 class="blog__title">
                                    <?= Html::encode($product->name) ?>
                                </h2>
                                <p class="blog__description">
                                    <?= Html::encode($product->{'description_' . $currentLang}) ?>
                                </p>

                                <div class="blog__footer">
                                    <div class="blog__reaction">
                                        <i class="bx bx-comment"></i>
                                        <span>
                                    <?= Yii::$app->formatter->asDecimal($commentsCount) ?>
                                </span>
                                    </div>

                                    <div class="blog__reaction">
                                        <i class="bx bx-show"></i>
                                        <span>
                                    <?= isset($viewedCounts[$product->id]) ? $viewedCounts[$product->id]['count'] : 0 ?>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

    </main>

    <!--==================== FOOTER ====================-->
    <footer class="footer">
        <div class="footer__container container">
            <h1 class="footer__title">Coffee.</h1>

            <div class="footer__content grid">
                <div class="footer__data">
                    <p class="footer__description">
                        Subscribe to our newsletter
                    </p>

                    <form class="footer__newsletter">
                        <input required placeholder="Your email..." type="email" class="footer__input">
                        <button class="footer__button">
                            <i class="bx bx-right-arrow-alt"></i>
                        </button>
                    </form>
                </div>
                <div class="footer__data">
                    <h2 class="footer__subtitle">Address</h2>
                    <p class="footer__information">
                        9876 Hacienda Av. <br>
                        Lima, La Libertad 123, Perú
                        <img src="<?= Yii::getAlias('@web/coffee/img/footerflag.png')?>" alt="" class="footer__flag">
                    </p>
                </div>
                <div class="footer__data">
                    <h2 class="footer__subtitle">Contact</h2>
                    <p class="footer__information">
                        +987654321 <br>
                        coffee@email.com
                    </p>
                </div>
                <div class="footer__data">
                    <h2 class="footer__subtitle">Office</h2>
                    <p class="footer__information">
                        Monday - Saturday <br>
                        9AM - 10PM
                    </p>
                </div>
            </div>

            <div class="footer__group">
                <ul class="footer__social">
                    <a target="_blank" href="https://www.facebook.com/profile.php?id=100069695989091" class="footer__social-link">
                        <i class="bx bxl-facebook"></i>
                    </a>
                    <a target="_blank" href="https://www.instagram.com/omonjon_8482?r=nametag" class="footer__social-link">
                        <i class="bx bxl-instagram"></i>
                    </a>
                    <a target="_blank" href="https://t.me/Sobirov_0202" class="footer__social-link">
                        <i class="bx bxl-telegram"></i>
                    </a>
                </ul>

                <div class="footer__copy">
                    &#169; by Omonjon Sobirov. All rigths reserved (2022)
                </div>
            </div>
        </div>
        </div>
    </footer>


    <!--========== SCROLL UP ==========-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="bx bx-up-arrow-alt"></i>
    </a>

    <script>
            document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.add-to-basket');

            buttons.forEach(button => {
            button.addEventListener('click', function (event) {
            event.preventDefault(); // Standart button amalini to‘xtatish

            const productId = this.getAttribute('data-product-id');

            fetch(`<?= Url::to(['basket/add']) ?>?id=${productId}`, {
            method: 'POST',
            headers: {
            'X-Requested-With': 'XMLHttpRequest', // So‘rovni AJAX sifatida aniqlash uchun
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>', // CSRF tokenini qo‘shish
        },
        })
            .then(response => response.json())
            .then(data => {
            if (data.status === 'success') {
            alert('Mahsulot savatga qo\'shildi'); // Muvoffaqiyatli qo‘shildi xabari
        } else {
            alert('Savatga qo‘shishda xatolik yuz berdi'); // Xatolik haqida xabar
        }
        })
            .catch(error => {
            alert('Xatolik yuz berdi'); // Umumiy xatolik xabari
            console.error('Xatolik:', error);
        });
        });
        });
        });

    </script>

    <?php
    $script = <<<JS
var isExpanded = false;

document.getElementById('seeMoreButton').addEventListener('click', function() {
    var hiddenItems = document.querySelectorAll('.specialty__category .specialty__group');

    if (isExpanded) {
        hiddenItems.forEach(function(item, index) {
            if (index >= 3) { // Hide all but the first 3 items
                item.classList.add('hidden');
            }
        });
        this.textContent = 'See more';
    } else {
        hiddenItems.forEach(function(item, index) {
            if (index >= 3) { // Show 3 more items
                item.classList.remove('hidden');
            }
        });
        // this.textContent = 'Show less';
    }

    isExpanded = !isExpanded;
});
JS;
    $this->registerJs($script);
    ?>

    <?php
    if (Yii::$app->request->get('scroll')) {
        $this->registerJs("
        $('html, body').animate({
            scrollTop: $('#target-section').offset().top
        }, 100);
    ");
    }
    ?>


<script>
    document.getElementById('see-more').addEventListener('click', function () {
        document.querySelectorAll('[data-index]').forEach(function (element) {
            element.style.display = 'block';
        });
        this.style.display = 'none';
    });

</script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
