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

$progress = $accumulated / $goal * 100;
$progress = round($progress, 0, PHP_ROUND_HALF_UP);
$status = "";
if($progress >= 100) {
  $status = "<span class='badge badge-success'>Successfully</span> completed!<hr/>";
}
if($completed) {
  $status = "Completed.<hr/>";
}
?>
<?= $status ?>
<div style="padding-left: 60px; padding-right: 60px; font-size: 12px">
  <div>Goal: <?= number_format($goal) ?> words over <?= $day_count ?> days.</div>
  <div>From <span style="padding: 0 5px 0 5px"><?= date('F d Y', strtotime($start)) ?></span> to <span style="padding: 0 5px 0 5px"><?= date('F d Y', strtotime($end)) ?></span></div>
  <div class="progress">
    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"><?= $progress ?>%</div>
  </div>
  <div><?= number_format($accumulated) ?> words written. <?= number_format($words_left) ?> words left.</div>
</div>
<hr/>
<div id="<?= json_decode($id) ?>" class="apexcharts-container">
    <widget-apexcharts :width="width" :height="height" :type="type" :chart-options="chartOptions" :series="series"></widget-apexcharts>
</div>
<?php
if($render) {
  $goal_threequarters = $goal * 0.75;
  $goal_half = $goal / 2;
  $goal_quart = $goal * 0.25;
  
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
    },
    markers: {
      size: 3,
    },
    annotations: {
      yaxis: [
        {
          y: $goal,
          borderColor: '#00E396',
          strokeDashArray: 0,
        },
        {
          y: $goal_threequarters,
          borderColor: '#00E396',
        },
        {
          y: $goal_half,
          borderColor: '#00E396',
          strokeDashArray: 8,
        },
        {
          y: $goal_quart,
          borderColor: '#00E396',
        }
      ]
    },
    title: { text: 'Overall Progress', style: { fontSize: '20px' }},
    series: $series,
    xaxis: {type: 'datetime'},
    yaxis: {
      max: $yaxis_max,
      min: 0,
      tickAmount: 8,
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
};
