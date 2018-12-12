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
        ]);

        $this->createTable('entry', [
            'id' => $this->primaryKey(),
            'plan_id' => $this->integer(),
            'date' => $this->dateTime()->notNull(),
            'amount' => $this->integer()->notNull()->defaultValue(1),
            'accumulate' => $this->boolean()->notNull()->defaultValue(1),
        ]);

        $this->createIndex(
            'idx-entry-plan_id',
            'entry',
            'plan_id'
        );

        /* foreign keys not supported by Sqlite?
        $this->addForeignKey(
            'fk-entry-plan_id',
            'entry',
            'plan_id',
            'plan',
            'id',
            'CASCADE'
        );
        */
    }

    public function down()
    {
        echo "m181202_202322_initial cannot be reverted.\n";
        return false;
    }
}
