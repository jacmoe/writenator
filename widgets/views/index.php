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
    type: $type
  },
  chartOptions: $chartOptions,
  series: $series,
  width: $width,
  height: $height
}

var chart = new ApexCharts(document.querySelector('#'+$id), options);

chart.render();

JS
);

