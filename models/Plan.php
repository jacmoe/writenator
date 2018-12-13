<?php

namespace app\models;

use Yii;

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
            [['title', 'start', 'end', 'goal', 'daycount'], 'required'],
            [['start', 'end', 'daycount'], 'safe'],
            [['goal'], 'integer'],
            [['daycount'], 'integer'],
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
        ];
    }

    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['plan_id' => 'id']);
    }
}
