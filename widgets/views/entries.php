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
<div style="padding-left: 30px">
    <div class="font-weight-light" style="font-size: 12px"><span style="background-color: #00E396">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Target Quota for each day.</div>
    <div class="font-weight-light" style="font-size: 12px"><span style="background-color: #FEB019">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Adjusted Target Quota based on current progress.</div>
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
        strokeDashArray: 0,
        label: {
          borderColor: '#FEB019',
          style: {
            color: '#FEB019',
            background: '#fff',
            fontSize: '14px'
          },
          text: ($adjustedgoal).toLocaleString('en')
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
            fontSize: '14px'
          },
          text: ($daygoal).toLocaleString('en')
        }
      }
    ]
  },
  title: { text: $title + ' - Daily Work', style: { fontSize: '20px' }},
  series: $series,
  xaxis: {type: 'datetime'},
  yaxis: {
    max: $yaxis_max,
    min: 0,
    tickAmount: 4,
    labels: {
      formatter: (value) => { return (value).toLocaleString('en') },
    }
  },
  width: '100%',
  height: '100%',
  tooltip: { enabled: true }
}

var chart = new ApexCharts(document.querySelector('#'+$id), options);

chart.render();

JS
);

