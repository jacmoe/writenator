<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-widget-apexcharts
 */

namespace app\widgets;

use app\models\Plan;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class ApexchartsWidget extends Widget
{

    public $plan_id;
    public $render = true;

    private $id = 'apexcharts-widget';
    private $series = [];

    public function init()
    {
        \Yii::setAlias('@apexchartsWidgetRoot', __DIR__);

        if ($this->plan_id === null) {
            throw new InvalidConfigException('plan_id should be specified.');
        }
    }

    public function getViewPath()
    {
        return \Yii::getAlias('@apexchartsWidgetRoot/views');
    }

    public function beforeRun()
    {
        return parent::beforeRun();
    }


    public function run()
    {
        $id = json_encode($this->getId());

        $plan = Plan::findOne(['id' => $this->plan_id]);
        $yaxis_max = $plan->goal;
        $day_count = $plan->daycount;
        $start = $plan->start;
        $end = $plan->end;

        $days_left = $day_count;
        $datetime1 = date_create();
        $datetime2 = date_create($end);
        $interval = date_diff($datetime1, $datetime2);
        $days_left = $interval->format('%R%a') + 1;

        $completed = false;
        if($days_left <= 0) {
            $completed = true;
        }

        $data = array();
        $normals = array();
        $daygoalnorm = round($plan->goal / $day_count, 0, PHP_ROUND_HALF_UP);
        $normalsacc = 0;

        $cur_max = 0;
        $accumulated = 0;
        foreach($plan->entries as $entry) {
            $normalsacc = $normalsacc + $daygoalnorm;
            $normals[] = [date("m/d/Y", strtotime($entry->date)), $normalsacc];
            if($entry->entered > 0) {
                $accumulated = $accumulated + $entry->amount;
                $data[] = [date("m/d/Y", strtotime($entry->date)), $accumulated];
            } else {
                $data[] = [date("m/d/Y", strtotime($entry->date)), null];
            }
            $cur_max = ($cur_max > $accumulated) ? $cur_max : $accumulated;
        }
        // make sure that cur_max is a multiple of a thousand, and if not, round up to nearest thousand
        $cur_max = (($cur_max % 1000) == 0) ? $cur_max : $cur_max - ($cur_max % 1000) + 1000;

        // make sure that yaxis_max is a multiple of a thousand, and if not, round up to nearest thousand
        $goal = $yaxis_max;
        $yaxis_max = (($yaxis_max % 1000) == 0) ? $yaxis_max : $yaxis_max - ($yaxis_max % 1000) + 1000;

        $yaxis_max = ($cur_max <= $yaxis_max) ? $yaxis_max : $cur_max;

        $this->series = [['name' => 'Words', 'data' => $data], ['name' => 'Goal', 'data' => $normals]];
        $series = json_encode($this->series);

        $words_left = $goal - $accumulated;
        if($words_left < 0) $words_left = 0;

        $render = $this->render;

        echo $this->render('chart', compact('id', 'series', 'yaxis_max', 'goal', 'accumulated', 'day_count', 'start', 'end', 'accumulated', 'words_left', 'completed', 'render'));
    }


}
