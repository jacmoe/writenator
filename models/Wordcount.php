<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wordcount".
 *
 * @property int $id
 * @property int $totalwords
 */
class Wordcount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wordcount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['totalwords'], 'integer'],
            [['totalwords'], 'required'],
            [['totalwords'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'totalwords' => Yii::t('app', 'Total Words'),
        ];
    }
}
