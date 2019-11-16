<?php

use yii\db\Migration;

/**
 * Class m191116_000510_stat
 */
class m191116_000510_stat extends Migration
{
    public function up()
    {
        $this->createTable('heat', [
            'id' => $this->primaryKey(),
            'date' => $this->dateTime()->notNull(),
            'entries' => $this->integer()->notNull(),
        ]);

        $this->createTable('wordcount', [
            'id' => $this->primaryKey(),
            'totalwords' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        echo "m191116_000510_stat cannot be reverted.\n";

        return false;
    }
}
