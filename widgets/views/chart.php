<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-widget-apexcharts
 */

/** @var $this \yii\web\View */
/** @var $id string */
/** @var $chartOptions string */
/** @var $series string */
/** @var $type string */
/** @var $width string */
/** @var $height string */
/** @var $timeout integer */

?>

<div id="<?= json_decode($id) ?>" class="apexcharts-container">
    <widget-apexcharts :width="width" :height="height" :type="type" :chart-options="chartOptions" :series="series"></widget-apexcharts>
</div>

<?php

$this->registerJs(<<<JS

var options = {
  chart: {
    type: 'line'
  },
  title: { text: $title, style: { fontSize: '20px' }},
  series: $series,
  xaxis: {type: 'datetime'},
  yaxis: { max: $yaxis_max, min: 0, tickAmount: 4},
  width: '100%',
  height: '100%',
  tooltip: { enabled: true }
}

var chart = new ApexCharts(document.querySelector('#'+$id), options);

chart.render();

JS
);

