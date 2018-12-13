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

class ApexchartsWidget extends Widget
{

    public $plan_id;
    public $title = "untitled";
    public $yaxis_max = 50000;

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
        $title = json_encode($this->title);

        $entries = Entry::find()->where(['plan_id' => $this->plan_id])->all();

        $data = array();

        $cur_max = 0;
        $accumulated = 0;
        foreach($entries as $entry) {
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
        $yaxis_max = $this->yaxis_max;
        $goal = $this->yaxis_max;
        $yaxis_max = (($yaxis_max % 1000) == 0) ? $yaxis_max : $yaxis_max - ($yaxis_max % 1000) + 1000;

        $yaxis_max = ($cur_max <= $yaxis_max) ? $yaxis_max : $cur_max;

        $this->series = [['name' => 'Words', 'data' => $data]];
        $series = json_encode($this->series);

        echo $this->render('chart', compact('id', 'title', 'series', 'yaxis_max', 'goal', 'accumulated'));
    }


}
