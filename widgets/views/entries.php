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

$daygoal = round($goal / $day_count, 0, PHP_ROUND_HALF_UP);

$this->registerJs(<<<JS

var options = {
  chart: {
    type: 'line',
    animations: {
      enabled: false
    },
    toolbar: {
      tools: {
        zoom : false,
        zoomin: false,
        zoomout: false,
        pan: false,
        reset: false
      }
    }
  },
  stroke: {
    width: 3,
    curve: 'smooth'
  },
  markers: {
    size: 3,
  },
  annotations: {
    yaxis: [
      {
        y: $adjustedgoal,
        borderColor: '#FEB019',
        label: {
          borderColor: '#FEB019',
          style: {
            color: '#FEB019',
            background: '#fff',
            fontSize: '16px'
          },
          text: "Adjusted Daily Goal : " + ($adjustedgoal).toLocaleString('en')
        }
      },
      {
        y: $daygoal,
        borderColor: '#00E396',
        label: {
          borderColor: '#00E396',
          style: {
            color: '#00E396',
            background: '#fff',
            fontSize: '16px'
          },
          text: "Average Daily Goal : " + ($daygoal).toLocaleString('en')
        }
      }
    ]
  },
  title: { text: $title, style: { fontSize: '20px' }},
  series: $series,
  xaxis: {type: 'datetime'},
  yaxis: { max: $yaxis_max, min: 0, tickAmount: 10},
  width: '100%',
  height: '100%',
  tooltip: { enabled: true }
}

var chart = new ApexCharts(document.querySelector('#'+$id), options);

chart.render();

JS
);

