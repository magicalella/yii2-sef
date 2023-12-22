<?php

use yii\db\Migration;

/**
 * Class m171226_122321_sef
 */
class m171226_122321_sef extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
	if ($this->db->driverName === 'mysql') {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	}
	$this->createTable('{{%sef}}', [
		'id' => $this->primaryKey(),
		'link' => $this->string()->notNull(),
		'link_sef' => $this->string()->notNull(),
        'meta_title' => $this->string(),
        'meta_description' => $this->text(),        
	], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171226_122321_sef cannot be reverted.\n";
        $this->dropTable('{{%sef}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171226_122321_sef cannot be reverted.\n";

        return false;
    }
    */
}
