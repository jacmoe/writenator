<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entry".
 *
 * @property int $id
 * @property int $plan_id
 * @property string $date
 * @property int $amount
 * @property bool $accumulate
 */
class Entry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'amount'], 'integer'],
            [['date', 'amount'], 'required'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plan_id' => Yii::t('app', 'Plan ID'),
            'date' => Yii::t('app', 'Date'),
            'amount' => Yii::t('app', 'Amount'),
            'accumulate' => Yii::t('app', 'Accumulate'),
        ];
    }
}
