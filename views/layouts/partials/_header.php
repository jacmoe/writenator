<?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
        'class' => 'navbar navbar-dark bg-dark navbar-static-top navbar-expand-lg navbar-frontend',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav mr-auto'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
        ],
    ]);
    NavBar::end();
    ?>
