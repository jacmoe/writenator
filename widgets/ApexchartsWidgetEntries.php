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

class ApexchartsWidgetEntries extends Widget
{

    public $plan_id;

    private $id = 'apexcharts-widget-entries';
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
        $goal = $plan->goal;
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

        $cur_max = 0;
        $sofar = 0;
        $sofar_days = 0;
        foreach($plan->entries as $entry) {
            if($entry->entered > 0) {
                $data[] = [date("m/d/Y", strtotime($entry->date)), $entry->amount];
            } else {
                $data[] = [date("m/d/Y", strtotime($entry->date)), null];
            }
            $sofar += $entry->amount;
            if($entry->entered > 0) $sofar_days++;
            $cur_max = ($cur_max > $entry->amount) ? $cur_max : $entry->amount;
        }
        // make sure that cur_max is a multiple of a thousand, and if not, round up to nearest thousand
        $cur_max = (($cur_max % 1000) == 0) ? $cur_max : $cur_max - ($cur_max % 1000) + 1000;
        
        $yaxis_max = $cur_max;

        $remaining_days = $day_count - $sofar_days;
        if($remaining_days < 2) {
            $adjustedgoal = round(($goal - $sofar), 0, PHP_ROUND_HALF_UP);
        } else {
            $adjustedgoal = round($goal / $day_count - 1, 0, PHP_ROUND_HALF_UP);
        }

        // If adjusted goal is higher than the y axis, make it longer
        $tadjustedgoal = $adjustedgoal;
        if($yaxis_max < $adjustedgoal) {
            $tadjustedgoal = (($tadjustedgoal % 1000) == 0) ? $tadjustedgoal : $tadjustedgoal - ($tadjustedgoal % 1000) + 1000;
            $yaxis_max = $tadjustedgoal;
        }

        $this->series = [['name' => 'Words', 'data' => $data]];
        $series = json_encode($this->series);


        echo $this->render('entries', compact('id', 'series', 'yaxis_max', 'goal', 'day_count', 'remaining_days', 'sofar', 'adjustedgoal', 'days_left'));
    }


}
