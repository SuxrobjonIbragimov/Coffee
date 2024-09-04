<?php

namespace archive;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories}}`.
 */
class m240821_191535_create_categories_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('categories');
    }

}
