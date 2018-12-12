<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-widget-apexcharts
 */

namespace app\widgets;

use app\models\Entry;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class ApexchartsWidgetEntries extends Widget
{

    public $plan_id;
    public $title = "untitled";
    public $yaxis_max = 1000;
    public $day_count = 1;

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
        $title = json_encode($this->title);

        $entries = Entry::find()->where(['plan_id' => $this->plan_id])->all();

        $data = array();

        $cur_max = 0;
        $sofar = 0;
        $sofar_days = 0;
        foreach($entries as $entry) {
            if($entry->accumulated > 0) {
                $data[] = [$entry->date, $entry->amount];
            } else {
                $data[] = [$entry->date, null];
            }
            $sofar += $entry->amount;
            if($entry->accumulated > 0) $sofar_days++;
            $cur_max = ($cur_max > $entry->amount) ? $cur_max : $entry->amount;
        }
        // make sure that cur_max is a multiple of a thousand, and if not, round up to nearest thousand
        $cur_max = (($cur_max % 1000) == 0) ? $cur_max : $cur_max - ($cur_max % 1000) + 1000;


        $yaxis_max = $cur_max;
        $goal = $this->yaxis_max;
        $day_count = $this->day_count;

        $this->series = [['name' => 'words', 'data' => $data]];
        $series = json_encode($this->series);

        $remaining_days = $day_count - $sofar_days;

        echo $this->render('entries', compact('id', 'title', 'series', 'yaxis_max', 'goal', 'day_count', 'remaining_days', 'sofar'));
    }


}
