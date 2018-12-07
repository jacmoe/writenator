<?php
$series = [
    [
        'name' => 'words',
        'data' => [
        ['05/06/2014 GMT', 10], 
        ['05/07/2014 GMT', 155], 
        ['05/08/2014 GMT', 1200] , 
        ['05/09/2014 GMT', 6000], 
        ['05/10/2014 GMT', 8900], 
        ['05/11/2014 GMT', 20000],
        ['05/12/2014 GMT', 31568],
        ['05/13/2014 GMT', 50148],
        ['05/14/2014 GMT', 60148],
        ['05/15/2014 GMT', 68451],
        ['05/16/2014 GMT', 71000],
        ['05/17/2014 GMT', 72898],
        ['05/18/2014 GMT', 88564],
        ['05/19/2014 GMT', 90000],
        ]],
];

$xaxis = [
    'type' => 'datetime',
];

echo app\widgets\ApexchartsWidget::widget([
    'type' => 'line', // default area
    'height' => '400', // default 350
    'width' => '500', // default 100%
    'series' => $series,
    'xaxis' => $xaxis
]);
?>