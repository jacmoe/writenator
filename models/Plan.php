<?php

namespace app\models;

use Yii;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * This is the model class for table "plan".
 *
 * @property int $id
 * @property string $title
 * @property string $start
 * @property string $end
 * @property int $goal
 * @property int $startamount
 * @property int $externalamount
 * @property int $externaldays
 * @property int $globalshow
 */
class Plan extends \yii\db\ActiveRecord
{
    public $numdays;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'start', 'end', 'goal'], 'required'],
            [['start', 'end'], 'safe'],
            [['goal', 'daycount', 'startamount', 'externalamount', 'externaldays'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'goal' => Yii::t('app', 'Goal'),
            'daycount' => Yii::t('app', 'Daycount'),
            'startamount' => Yii::t('app', 'Starting amount'),
            'externalamount' => Yii::t('app', 'External amount'),
            'externaldays' => Yii::t('app', 'External Days'),
            'globalshow' => Yii::t('app', 'Show global goal'),
        ];
    }

    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['plan_id' => 'id']);
    }

    public function getDaycount()
    {
        $period = new CarbonPeriod($this->start, $this->end);
        return $period->count();
    }

    public function getStatus()
    {
        $period = new CarbonPeriod($this->start, $this->end);
        if($period->startsAfter()) {
            return 'notstarted';
        }
        if($period->isInProgress()) {
            return 'progressing';
        }
        if($period->isEnded()) {
            return 'ended';
        }

        return $period->count();
    }

    public function fixPreviousEntries()
    {
        foreach($this->entries as $entry) {
            $date = Carbon::createFromFormat('Y-m-d', $entry->date);
            // if the date of the entry is prior to today and it wasn't entered, set it to entered
            if(($date->lessThan(Carbon::today())) and ($entry->entered == false)) {
                $entry->entered = true;
                $entry->save();
            }
        }
    }
}
