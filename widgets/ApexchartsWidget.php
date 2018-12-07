<?php
/**
 * @copyright Copyright (c) 2018
 * @author Alexandr Kozhevnikov <onmotion1@gmail.com>
 * @package yii2-widget-apexcharts
 */

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 *
 * @property ActiveRecord|null $model
 */
class ApexchartsWidget extends Widget
{

    public $id = 'apexcharts-widget';
    public $chartOptions = [];
    public $series = [];
    public $type = 'line';
    public $width = '100%';
    public $height = 350;

    public function init()
    {
        \Yii::setAlias('@apexchartsWidgetRoot', __DIR__);

        parent::init();
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
        parent::run();

        $id = json_encode($this->getId());
        $chartOptions = json_encode((object)$this->chartOptions);
        $series = json_encode($this->series);
        $type = json_encode($this->type);
        $width = json_encode($this->width);
        $height = json_encode((string)$this->height);

        echo $this->render('index', compact('id', 'chartOptions', 'series', 'type', 'width', 'height'));
    }


}
