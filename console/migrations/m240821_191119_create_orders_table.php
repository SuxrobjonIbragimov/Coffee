<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m240821_191119_create_orders_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'customer_name' => $this->string(255)->notNull(),
            'customer_email' => $this->string(255)->notNull(),
            'total_price' => $this->decimal(10,2)->notNull(),

        ]);
    }

    public function safeDown()
    {
        $this->dropTable('orders');
    }

}
