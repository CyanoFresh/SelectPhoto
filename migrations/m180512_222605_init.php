<?php

use yii\db\Migration;

/**
 * Class m180512_222605_init
 */
class m180512_222605_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('project', [
            'id' => $this->primaryKey()->unsigned(),
            'active' => $this->boolean()->defaultValue(true),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createTable('link', [
            'id' => $this->primaryKey()->unsigned(),
            'active' => $this->boolean()->defaultValue(true),
            'link' => $this->string(36)->notNull(),
            'name' => $this->string()->notNull(),
            'project_id' => $this->integer()->unsigned(),
            'submitted' => $this->boolean()->defaultValue(false),
            'allow_comment' => $this->boolean()->defaultValue(true),
            'disable_after_submit' => $this->boolean()->defaultValue(true),
            'watermark' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'submitted_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createIndex('idx-link-project_id', 'link', 'project_id');
        $this->createIndex('idx-link-link', 'link', 'link', true);

        $this->addForeignKey('fk-link-project_id', 'link', 'project_id', 'project', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('photo', [
            'id' => $this->primaryKey()->unsigned(),
            'link_id' => $this->integer()->unsigned(),
            'selected' => $this->boolean()->defaultValue(false),
            'filename' => $this->string(100)->notNull(),
            'comment' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-photo-link_id', 'photo', 'link_id');

        $this->addForeignKey('fk-photo-link_id', 'photo', 'link_id', 'link', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->dropTable('project');
        $this->dropTable('link');
        $this->dropTable('photo');

        $this->execute("SET foreign_key_checks = 1;");
    }
}
