<?php

use yii\db\Migration;

/**
 * Class m180610_193056_add_sort_order_to_photo
 */
class m180610_193056_add_sort_order_to_photo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('photo', 'sort_order', $this->smallInteger()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('photo', 'sort_order');
    }
}
