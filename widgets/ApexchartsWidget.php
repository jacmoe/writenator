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
        foreach($entries as $entry) {
            $data[] = [$entry->date, $entry->amount];
            $cur_max = ($cur_max > $entry->amount) ? $cur_max : $entry->amount;
        }

        $yaxis_max = ($cur_max <= $this->yaxis_max) ? $this->yaxis_max : $cur_max;

        $this->series = [['name' => 'words', 'data' => $data]];
        $series = json_encode($this->series);

        echo $this->render('chart', compact('id', 'title', 'series', 'yaxis_max'));
    }


}
