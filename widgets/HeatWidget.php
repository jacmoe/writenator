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

        $heat = Heat::find()->all();
        foreach($heat as $entry) {
            $heats[$entry->date] = $entry->entries;
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
        
        echo $this->render('heat', compact('series', 'id'));
    }


}
