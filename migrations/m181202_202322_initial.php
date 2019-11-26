<?php

use yii\db\Migration;

/**
 * Class m181202_202322_initial
 */
class m181202_202322_initial extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('plan', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'start' => $this->dateTime()->notNull(),
            'end' => $this->dateTime()->notNull(),
            'goal' => $this->integer()->notNull(),
            'startamount' => $this->integer()->notNull()->defaultValue(0),
            'endamount' => $this->integer()->notNull()->defaultValue(0),
            'globalshow' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createTable('entry', [
            'id' => $this->primaryKey(),
            'plan_id' => $this->integer(),
            'date' => $this->dateTime()->notNull(),
            'amount' => $this->integer()->notNull()->defaultValue(1),
            'entered' => $this->integer()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx-entry-plan_id',
            'entry',
            'plan_id'
        );

    }

    public function down()
    {
        echo "m181202_202322_initial cannot be reverted.\n";
        return false;
    }
}
