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

$goal_threequarters = $goal * 0.75;
$goal_half = $goal / 2;
$goal_quart = $goal * 0.25;

$this->registerJs(<<<JS

var options = {
  chart: {
    type: 'line'
  },
  annotations: {
    yaxis: [
      {
        y: $goal,
        borderColor: '#00E396',
        label: {
          borderColor: '#00E396',
          style: {
            color: '#fff',
            background: '#00E396',
            fontSize: '16px'
          },
          text: "Goal : " + ($goal).toLocaleString('en')
        }
      },
      {
        y: $goal_threequarters,
        borderColor: '#00E396',
        label: {
          borderColor: '#00E396',
          style: {
            color: '#fff',
            background: '#00E396',
            fontSize: '16px'
          },
          text: "3/4"
        }
      },
      {
        y: $goal_half,
        borderColor: '#00E396',
        label: {
          borderColor: '#00E396',
          style: {
            color: '#fff',
            background: '#00E396',
            fontSize: '16px'
          },
          text: "1/2"
        }
      },
      {
        y: $goal_quart,
        borderColor: '#00E396',
        label: {
          borderColor: '#00E396',
          style: {
            color: '#fff',
            background: '#00E396',
            fontSize: '16px'
          },
          text: "1/4"
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

