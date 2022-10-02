<?php

use app\models\socks\Socks;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%socks}}`.
 */
class m221002_120317_create_socks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Socks::tableName(), [
            'id' => $this->primaryKey(),
            'color' => $this->string(100)->notNull(),
            'quantity' => $this->integer()->notNull(),
            'cotton_part' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Socks::tableName());
    }
}
