<div id="<?= json_decode($id) ?>" class="apexcharts-container">
    <widget-apexcharts :width="width" :height="height" :type="type" :chart-options="chartOptions" :series="series"></widget-apexcharts>
</div>
<?php
$render = true;
if($render) {
  
  $this->registerJs(<<<JS
  
  var options = {
    chart: {
      height: 200,
      type: 'heatmap',
    },
    dataLabels: {
                enabled: false
            },
            colors: ["#663F59"],
    series: $series,
    title: {
                text: 'Writing Heatmap this quarter'
            },
  }
  
  var chart = new ApexCharts(document.querySelector('#'+$id), options);
  
  chart.render();
  
JS
  );
};
echo '<div>Current streak: '. $streak_count . ' days.</div>';