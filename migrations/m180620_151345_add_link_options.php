<?php

use yii\db\Migration;

class m180620_151345_add_link_options extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('link', 'max_photos', $this->smallInteger()->unsigned()->defaultValue(0)->after('submitted'));
        $this->addColumn('link', 'show_tutorial', $this->boolean()->defaultValue(true)->after('watermark'));
        $this->addColumn('link', 'disable_right_click', $this->boolean()->defaultValue(true)->after('show_tutorial'));
        $this->addColumn('link', 'greeting_message', $this->text()->after('show_tutorial'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('link', 'max_photos');
        $this->dropColumn('link', 'show_tutorial');
        $this->dropColumn('link', 'greeting_message');
        $this->dropColumn('link', 'disable_right_click');
    }
}
