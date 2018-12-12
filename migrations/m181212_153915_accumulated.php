<?php

use yii\db\Migration;

/**
 * Class m181212_153915_accumulated
 */
class m181212_153915_accumulated extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('entry', 'accumulated', $this->integer());
    }

    public function down()
    {
        echo "m181212_153915_accumulated cannot be reverted.\n";

        return false;
    }
}
