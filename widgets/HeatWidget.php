<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-widget-apexcharts
 */

namespace app\widgets;

use app\models\Heat;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use League\Period;

class HeatWidget extends Widget
{

    private $id = 'heat-widget';
    private $series = [];

    public function init()
    {
        \Yii::setAlias('@heatWidgetRoot', __DIR__);
    }

    public function getViewPath()
    {
        return \Yii::getAlias('@heatWidgetRoot/views');
    }

    public function beforeRun()
    {
        return parent::beforeRun();
    }


    public function run()
    {
        $id = json_encode($this->getId());
        $heats = array();

        $heat = Heat::find()->orderBy('date')->all();
        foreach($heat as $entry) {
            $heats[$entry->date] = $entry->entries;
        }

        $streak = array_reverse($heats, false);
        $streak_count = 0;
        $today_date = date_create(date('Y-m-d'));
        $counter = -1;
        foreach($streak as $date => $activity) {
            $counter = $counter + 1;
            $the_date = date_create($date);
            $interval = date_diff($the_date, $today_date)->format('%d');
            if($interval <> $counter) {
                //echo 'streak was broken<br/>';
            break;
            }
            if($activity == 0) {
                //echo 'streak was broken at ' . $streak_count . '<br/>';
            break;
            }
            $streak_count = $streak_count + 1;
        }

        $quarter = Period\quarter(date('Y-m-d'));
        foreach($quarter->split('1 MONTH') as $month) {
            $data = array();
            foreach($month->split('1 DAY', \DatePeriod::EXCLUDE_START_DATE) as $day) {
                $date = date('Y-m-d', $day->getStartDate()->getTimestamp());
                if(array_key_exists($date, $heats)) {
                    $data[] = array('x' => $day->getStartDate()->format('d'), 'y' => $heats[$date]);
                } else {
                    $data[] = array('x' => $day->getStartDate()->format('d'), 'y' => 0);
                }
            }
            $this->series[] = ['name' => $month->getStartDate()->format('F'), 'data' => $data];
        }
        $series = json_encode(array_reverse($this->series, false));
        
        echo $this->render('heat', compact('series', 'id', 'streak_count'));
    }


}
