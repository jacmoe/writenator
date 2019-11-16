<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "heat".
 *
 * @property int $id
 * @property string $date
 * @property int $entries
 */
class Heat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'heat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entries'], 'integer'],
            [['date', 'entries'], 'required'],
            [['date', 'entries'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'entries' => Yii::t('app', 'Entries'),
        ];
    }
}
