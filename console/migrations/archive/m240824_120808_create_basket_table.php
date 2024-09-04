<?php

namespace archive;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%basket}}`.
 */
class m240824_120808_create_basket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%basket}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Creating index for product_id
        $this->createIndex(
            'idx-basket-product_id',
            '{{%basket}}',
            'product_id'
        );

        // Creating index for user_id
        $this->createIndex(
            'idx-basket-user_id',
            '{{%basket}}',
            'user_id'
        );

        // Adding foreign key for table `products`
        $this->addForeignKey(
            'fk-basket-product_id',
            '{{%basket}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // Adding foreign key for table `user`
        $this->addForeignKey(
            'fk-basket-user_id',
            '{{%basket}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Dropping foreign key for table `products`
        $this->dropForeignKey(
            'fk-basket-product_id',
            '{{%basket}}'
        );

        // Dropping foreign key for table `user`
        $this->dropForeignKey(
            'fk-basket-user_id',
            '{{%basket}}'
        );

        // Dropping indexes
        $this->dropIndex(
            'idx-basket-product_id',
            '{{%basket}}'
        );

        $this->dropIndex(
            'idx-basket-user_id',
            '{{%basket}}'
        );

    }
}
