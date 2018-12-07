<?php
$series = [
    [
        'name' => 'sales',
        'data' => [30,40,35,50,49,60,70,91,125],
        ],
];

$categories = [ 'type' => 'datetime', 'categories' => ['02-10-2017', '02-11-2017', '02-12-2017', '02-13-2017', '02-14-2017', '02-15-2017', '02-16-2017', '02-17-2017', '02-18-2017' ]];

echo app\widgets\ApexchartsWidget::widget([
    'type' => 'line', // default area
    'height' => '400', // default 350
    'width' => '500', // default 100%
    'chartOptions' => [
        'chart' => [
            'toolbar' => [
                'show' => true,
                'autoSelected' => 'zoom'
            ],
        ],
    ],
    'series' => $series,
    'xaxis' => $categories
]);
?>