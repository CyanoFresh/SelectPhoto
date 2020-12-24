<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m180512_222605_init
 */
class m201212_222605_add_user_table extends Migration
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

        $this->createTable('user', [
            'id' => $this->primaryKey()->unsigned(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addColumn('project', 'user_id', $this->integer()->unsigned()->after('id'));
        $this->addColumn('link', 'user_id', $this->integer()->unsigned()->after('id'));

        $this->createIndex('idx-project-user_id', 'project', 'user_id');
        $this->createIndex('idx-link-user_id', 'link', 'user_id');

        $this->addForeignKey('fk-project-user_id', 'project', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-link-user_id', 'link', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        \app\models\Link::updateAll([
            'user_id' => 1,
        ]);

        \app\models\Project::updateAll([
            'user_id' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");

        $this->dropTable('user');

        $this->dropColumn('project', 'user_id');
        $this->dropColumn('link', 'user_id');

        $this->execute("SET foreign_key_checks = 1;");
    }
}
