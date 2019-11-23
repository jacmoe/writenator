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

$daygoal = round($goal / $day_count, 0, PHP_ROUND_HALF_UP);
$wordsleft_today = $adjustedgoal - $today_entry;
if ($wordsleft_today < 0) {
  $wordsleft_today = 0;
}
$day_number = $day_count - $days_left;
if ($day_number < 0) {
  $day_number = 0;
  $average = $sofar;
} else {
  $average = $sofar / $day_number;
}
$progress = ($day_number / $day_count) * 100;
$progress = round($progress, 0, PHP_ROUND_HALF_UP);
if($progress >= 100) {
  $statuss = "<span class='badge badge-success'>Successfully</span> completed!<hr/>";
}
?>
<div style="padding-left: 60px; padding-right: 60px; font-size: 12px">
<hr/>
    <div>Day <?= $day_number ?> out of <?= $day_count ?>.</div>
      <div class="progress">
    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"><?= $progress ?>%</div>
  </div>
</div>
<hr/>

<div id="<?= json_decode($id) ?>" class="apexcharts-container">
    <widget-apexcharts :width="width" :height="height" :type="type" :chart-options="chartOptions" :series="series"></widget-apexcharts>
</div>
<div style="padding-left: 30px">
    <div><?php if($status == 'notstarted') echo 'Not started yet.'?><?php if($status == 'progressing') echo $days_left . ' days left.'?><?php if($status == 'ended') echo ' Ended ' . $time_ago; ?></div>
    <div style="font-size: 12px"><span style="background-color: #00E396">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Target Quota for each day : <?= number_format($daygoal) ?> words.</div>
    <div style="font-size: 12px"><span style="background-color: #FEB019">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Adjusted Target Quota based on current progress: <?= number_format($adjustedgoal) ?> words.</div>
    <div style="font-size: 12px"><span style="background-color: #008FFB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Average Wordcount for each day: <?= number_format($average) ?> words.</div>
    <div style="font-size: 12px">Words left to write today: <?=  number_format($wordsleft_today) ?></div>
</div>

<?php
if($render) {

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
      },
      {
        y: $daygoal,
        borderColor: '#00E396',
      },
      {
        y: $average,
        borderColor: '#008FFB',
      }
    ]
  },
  title: { text: 'Daily Work', style: { fontSize: '20px' }},
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
}
