<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
//$this->registerJs("var $ = jQuery", \yii\web\View::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container">
    <div class="header">
        <?php
            NavBar::begin([
                'brandLabel' => \Yii::t('app', 'My Company'),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar navbar-default',
                ],
            ]);
                if (Yii::$app->user->isGuest) {
                    echo Nav::widget([
                        'options' => ['class' => 'nav nav-pills pull-right'],
                        'items' => [
                            ['label' => \Yii::t('app', 'Login'), 'url' => ['/user/login']],
                        ],
                    ]);
                }
                else {
                    echo Nav::widget([
                        'options' => ['class' => 'nav nav-pills pull-right'],
                        'items' => [
                            ['label' => \Yii::t('app', 'Users online ({actUsers})', ['actUsers' => \app\models\Stat::getActiveUsersCount()]), 'url' => ['/stat/online']],
                            ['label' => \Yii::t('app', 'Profile'), 'url' => ['/user/settings/profile']],
                            [
                                'label' => \Yii::t('app', 'Logout {username}', ['username' => @\Yii::$app->user->identity->username]),
                                'url' => ['/user/logout'],
                                'linkOptions' => ['data-method' => 'post']
                            ],
                        ],
                    ]);
                }
            NavBar::end();
        ?>
    </div>

    <?php /*echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ])*/ ?>
    <?= $content ?>
</div>
<?php $this->endBody() ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68690448-2', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
<?php $this->endPage() ?>
