<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_details}}`.
 */
class m240821_191200_create_order_details_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('order_details', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),

        ]);

        $this->addForeignKey(
            'fk-order_details-order_id',
            'order_details',
            'order_id',
            'orders',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_details-product_id',
            'order_details',
            'product_id',
            'products',
            'id',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-order_details-order_id', 'order_details');
        $this->dropForeignKey('fk-order_details-product_id', 'order_details');
        $this->dropTable('order_details');
    }

}
