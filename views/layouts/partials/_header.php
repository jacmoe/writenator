<?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/android-icon-36x36.png', ['alt' => Yii::$app->name, 'title' => Yii::$app->name]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
        'class' => 'navbar navbar-dark bg-dark navbar-static-top navbar-expand-lg navbar-frontend',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav mr-auto'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Plans', 'url' => ['/plan/index']],
            ['label' => 'Corkboard', 'url' => ['/corkboard/index']],
        ],
    ]);
    NavBar::end();
    ?>
