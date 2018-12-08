<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'start',
            'end',
            'goal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
