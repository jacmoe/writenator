<?php
$series = [
    [
        'name' => 'sales',
        'data' => [30,40,35,50,49,60,70,91,125],
        ],
];

$categories = [1991,1992,1993,1994,1995,1996,1997, 1998,1999];

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
        'xaxis' => [
            'categories' => $categories,
        ],
    ],
    'series' => $series
]);
?>