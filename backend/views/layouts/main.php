<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top justify-content-center']
    ]);

    $menuItems = [
        // Admin Roles
        ['label' => Html::tag('i', '', ['class' => 'fa fa-user-tie']) . ' Admin Roles',
            'url' => ['/admin_roles/index'],
            'visible' => Yii::$app->user->can('Admin_roles')],

        // Users
        ['label' => Html::tag('i', '', ['class' => 'fa fa-users']) . ' Users',
            'url' => ['/users/index'],
            'visible' => Yii::$app->user->can('Users')],

        // Products
        ['label' => Html::tag('i', '', ['class' => 'fa fa-box']) . ' Products',
            'url' => ['/products/index'],
            'visible' => Yii::$app->user->can('Products')],

        // Categories
        ['label' => Html::tag('i', '', ['class' => 'fa fa-list']) . ' Categories',
            'url' => ['/categories/index'],
            'visible' => Yii::$app->user->can('Categories')],

        // Orders
        ['label' => Html::tag('i', '', ['class' => 'fa fa-shopping-cart']) . ' Orders',
            'url' => ['/orders/index'],
            'visible' => Yii::$app->user->can('Orders')],

        // Specifications
        ['label' => Html::tag('i', '', ['class' => 'fa fa-box']) . ' Specifications',
            'url' => ['/specifications/index'], 'visible' => Yii::$app->user->can('viewSpecifications')],

        // Comments
        ['label' => Html::tag('i', '', ['class' => 'fa fa-comment']) . ' Comment',
            'url' => ['/comment/index'], 'visible' => Yii::$app->user->can('viewComments')],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);

    if (Yii::$app->user->isGuest) {
        echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => 'btn btn-link login text-decoration-none']), ['class' => 'd-flex']);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                Html::tag('i', '', ['class'=>'fa fa-sign-out-alt']) . ' Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }

    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
