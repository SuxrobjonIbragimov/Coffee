<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_images}}`.
 */
class m240821_191016_create_product_images_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('product_images', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'image_file_name' => $this->string(255)->notNull(),

        ]);

        $this->addForeignKey(
            'fk-product_images-product_id',
            'product_images',
            'product_id',
            'products',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product_images-product_id', 'product_images');
        $this->dropTable('product_images');
    }

}
