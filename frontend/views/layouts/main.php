<?php

use frontend\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;

\frontend\assets\CoffeeAsset::register($this);
/* @var $this yii\web\View */
/* @var $products \frontend\models\Product[] */

$products = Product::find()->with('images', 'categories')->all();

$groupedProducts = [];
foreach ($products as $product) {
    $categoryName = $product->categories ? $product->categories->cname_uz : 'Uncategorized';
    $groupedProducts[$categoryName][] = $product;
}
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">

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
    </head>

    <body>
    <!--==================== LOADER ====================-->
    <!--        <div class="load" id="load">-->
    <!--            <img src="--><?php //= Yii::getAlias('@web/coffee/img/loadcoffee.gif')?><!--" alt="Load Gif" class="load__gif">-->
    <!--        </div>-->

    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="" class="nav__logo">
                <img src="<?= Yii::getAlias('@web/coffee/img/logo.png')?>" alt="Logo" class="nav__logo-img">
                Coffee.
            </a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link">Home</a>
                    </li>
                    <li class="nav__item">
                        <a href="#products" class="nav__link">Products</a>
                    </li>
                    <li class="nav__item">
                        <a href="#premium" class="nav__link">Premium</a>
                    </li>
                    <li class="nav__item">
                        <a href="#blog" class="nav__link">Blog</a>
                    </li>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a style="color: white" href="<?= Url::to(['/site/signup']) ?>"><i style="font-size: 23px" class="fa-solid fa-user"></i></a>
                    <?php else: ?>
                        <a href="<?= Url::to(['/profile/profile']) ?>"><i style="font-size: 23px" class="fa-solid fa-user"></i></a>
                    <?php endif; ?>
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
                        Choose Your Favorite Coffee And Enjoy<span>.</span>
                    </h1>
                    <p class="home__description">
                        Buy the best and delicious coffees.
                    </p>

                    <div class="home__data">
                        <div class="home__data-group">
                            <h2 class="home__data-number">120K</h2>
                            <h3 class="home__data-title">Testimonials</h3>
                            <p class="home__data-description">
                                Testimonials from various customers who trust us.
                            </p>
                        </div>

                        <div class="home__data-group">
                            <h2 class="home__data-number">340+</h2>
                            <h3 class="home__data-title">Exclusive Product</h3>
                            <p class="home__data-description">
                                Premium preparation with quality ingredients.
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
                        Specialty coffees that make you happy and cheer you up!
                    </h2>

                    <div class="">
                        <a href="#!" class="button specialty__button">See more</a>
                    </div>
                </div>

                <div class="specialty__category">
                    <div class="specialty__group specialty__line">
                        <img src="<?= Yii::getAlias('@web/coffee/img/specialty1.png')?>" alt="Specialty img" class="specialty__img">

                        <h3 class="specialty__title">Selected Coffee</h3>
                        <p class="specialty__description">
                            We select the best premium coffee, for a true taste.
                        </p>
                    </div>

                    <div class="specialty__group specialty__line">
                        <img src="<?= Yii::getAlias('@web/coffee/img/specialty2.png')?>" alt="Specialty img" class="specialty__img">

                        <h3 class="specialty__title">Delicious Cookies</h3>
                        <p class="specialty__description">
                            Enjoy your coffee with some hot cookies
                        </p>
                    </div>

                    <div class="specialty__group">
                        <img src="<?= Yii::getAlias('@web/coffee/img/specialty3.png')?>" alt="Specialty img" class="specialty__img">

                        <h3 class="specialty__title">Enjoy at Home</h3>
                        <p class="specialty__description">
                            Enjoy the best coffee in the comfort of your home.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--==================== PRODUCTS ====================-->
        <section class="products section" id="products">
            <div class="products__container container">
                <h2 class="section__title">
                    Choose our delicious and best products
                </h2>

                <div class="products__filter-container">
                    <ul class="products__filters">
                        <?php
                        $i = 0;
                        foreach ($groupedProducts as $categoryName => $productsInCategory):
                            // Show first 3 categories by default, hide the rest
                            $hiddenClass = ($i >= 3) ? 'hidden-category' : '';
                            ?>
                            <li class="products__item products__line <?= $hiddenClass ?>" data-filter=".<?= strtolower(Html::encode($categoryName)) ?>">
                                <h3 class="products__title"><?= Html::encode($categoryName) ?></h3>
                                <span class="products__stock"><?= count($productsInCategory) ?> products</span>
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
                                        <img style="height: 13rem" src="<?= Yii::getAlias('@web/uploads/' . $product->images[0]->image_file_name) ?>" class="products__img" alt="<?= Html::encode($product->name_uz) ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="products__data">
                                    <h2 class="products__price">$<?= Html::encode($product->price) ?></h2>
                                    <h3 class="products__name"><?= Html::encode($product->name_uz) ?></h3>

                                    <button class="button products__button">
                                        <i class="bx bx-shopping-bag"></i>
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
                    We offer a premium and better quality preparation just for you!
                </h2>

                <div class="quality__content grid">
                    <div class="quality__images">
                        <img src="<?= Yii::getAlias('@web/coffee/img/quality1.png')?>" alt="Quality" class="quality__img-big">
                        <img src="<?= Yii::getAlias('@web/coffee/img/quality2.png')?>" alt="Quality" class="quality__img-small">
                    </div>

                    <div class="quality__data">
                        <h1 class="quality__title">Premium Coffee</h1>
                        <h2 class="quality__price">$94.99</h2>
                        <span class="quality__special">Especial Price</span>
                        <p class="quality__description">
                            We are delighted with our coffee. That's why you get the best
                            premium coffee plus the kettle made of resistant materials that
                            you see in the image, for a special price.
                        </p>

                        <div class="quality__buttons">
                            <button class="button">
                                Buy Now
                            </button>

                            <a href="#!" class="quality__button">
                                See more
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>
                    </div>
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
        <section class="blog section" id="blog">
            <div class="blog__container container">
                <h2 class="section__title">
                    Our Blogs Coffee with trending topic for this week
                </h2>

                <div class="blog__content grid">
                    <article class="blog__card">
                        <div class="blog__image">
                            <img src="<?= Yii::getAlias('@web/coffee/img/blog1.png')?>" alt="BLog" class="blog__img">
                            <a href="#!" class="blog__button">
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <div class="blog__data">
                            <h2 class="blog__title">
                                10 Coffee Recommendations
                            </h2>
                            <p class="blog__description">
                                10 Coffee Recommendations
                                The blogs about coffee will help you a lot about
                                how it is prepared, its waiting time, for a good
                                quality coffee.
                            </p>

                            <div class="blog__footer">
                                <div class="blog__reaction">
                                    <i class="bx bx-comment"></i>
                                    <span>45</span>
                                </div>

                                <div class="blog__reaction">
                                    <i class="bx bx-show"></i>
                                    <span>76,5K</span>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="blog__card">
                        <div class="blog__image">
                            <img src="<?= Yii::getAlias('@web/coffee/img/blog2.png')?>" alt="BLog" class="blog__img">
                            <a href="#!" class="blog__button">
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <div class="blog__data">
                            <h2 class="blog__title">
                                12 Benefits Of Drinking Coffee
                            </h2>
                            <p class="blog__description">
                                The blogs about coffee will help you a lot about
                                how it is prepared, its waiting time, for a good
                                quality coffee.
                            </p>

                            <div class="blog__footer">
                                <div class="blog__reaction">
                                    <i class="bx bx-comment"></i>
                                    <span>77</span>
                                </div>

                                <div class="blog__reaction">
                                    <i class="bx bx-show"></i>
                                    <span>234,7K</span>
                                </div>
                            </div>
                        </div>
                    </article>
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

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
