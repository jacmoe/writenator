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
use Carbon\Carbon;
use Carbon\CarbonInterface;


class ApexchartsWidgetEntries extends Widget
{

    public $plan_id;
    public $render = true;

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
        if($days_left < 0) {
            $completed = true;
            $days_left = 0;
        }

        $endamount = $plan->externalamount;
        $externaldays = $plan->externaldays;
        $globalshow = $plan->globalshow;

        $data = array();

        $cur_max = 0;
        $sofar = 0;
        $today_entry = 0;
        $day_date = date("m/d/Y");
        foreach($plan->entries as $entry) {
            $entry_date = date("m/d/Y", strtotime($entry->date));
            if($entry->entered > 0) {
                $data[] = [$entry_date, $entry->amount];
                if($entry_date == $day_date) {
                    $today_entry = $entry->amount;
                }
            } else {
                $data[] = [$entry_date, null];
            }
            $sofar += $entry->amount;
            $cur_max = ($cur_max > $entry->amount) ? $cur_max : $entry->amount;
        }
        // make sure that cur_max is a multiple of a thousand, and if not, round up to nearest thousand
        $cur_max = (($cur_max % 1000) == 0) ? $cur_max : $cur_max - ($cur_max % 1000) + 1000;
        
        $daygoal = round(($goal - $plan->startamount) / $day_count, 0, PHP_ROUND_HALF_UP);

        $yaxis_max = $cur_max;
        // If the y axis is 0, then set it to two times daygoal, to nearest thousands
        if($yaxis_max == 0) {
            $yaxis_max = ((($daygoal % 1000) == 0) ? $daygoal : $daygoal - ($daygoal % 1000) + 1000) * 2;
        }

        if(($goal - $plan->startamount) - $sofar == 0) {
            $adjustedgoal = 0;
        } else {
            if($days_left <= 0) {
                $adjustedgoal = $daygoal;
            } else {
                if($globalshow) {
                    $adjustedgoal = round((($goal - $plan->startamount + $endamount) - ($sofar - $today_entry)) / ($days_left + $externaldays), 0, PHP_ROUND_HALF_UP);
                } else {
                    $adjustedgoal = round((($goal - $plan->startamount) - ($sofar - $today_entry)) / $days_left, 0, PHP_ROUND_HALF_UP);
                }
            }
        }
        if ($adjustedgoal < 0) {
            $adjustedgoal = 0;
        }
        if($days_left > $day_count) {
            $adjustedgoal = 0;
        }
        if (($day_count - $days_left)== 1) $adjustedgoal = $daygoal;

        // If adjusted goal is higher than the y axis, make it longer
        $tadjustedgoal = $adjustedgoal;
        if($yaxis_max < $adjustedgoal) {
            $tadjustedgoal = (($tadjustedgoal % 1000) == 0) ? $tadjustedgoal : $tadjustedgoal - ($tadjustedgoal % 1000) + 1000;
            $yaxis_max = $tadjustedgoal;
        }

        $time_ago = Carbon::parse($plan->end)->diffForHumans();
        $this->series = [['name' => 'Words', 'data' => $data]];
        $series = json_encode($this->series);

        $render = $this->render;
        $status = $plan->status;

        $startamount = $plan->startamount;

        echo $this->render('entries', compact('id', 'render', 'completed', 'status', 'series', 'yaxis_max', 'goal', 'startamount', 'daygoal', 'day_count', 'sofar', 'adjustedgoal', 'days_left', 'time_ago', 'today_entry'));
    }


}
