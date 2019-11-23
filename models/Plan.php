<?php

namespace app\models;

use Yii;
use Carbon\CarbonPeriod;

/**
 * This is the model class for table "plan".
 *
 * @property int $id
 * @property string $title
 * @property string $start
 * @property string $end
 * @property int $goal
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
            [['goal', 'daycount'], 'integer'],
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
}
