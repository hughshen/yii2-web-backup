<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cms_relationship`.
 */
class m170622_152821_create_cms_relationship_table extends Migration
{
    public $tableName;
    public $postTable;
    public $termTable;

    public function init()
    {
        parent::init();
        $this->tableName = '{{%cms_relationship}}';
        $this->postTable = '{{%cms_post}}';
        $this->termTable = '{{%cms_term}}';
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'term_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `post_id`
        $this->createIndex(
            'idx-cms_relationship-post_id',
            $this->tableName,
            'post_id'
        );

        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-cms_relationship-post_id',
            $this->tableName,
            'post_id',
            $this->postTable,
            'id',
            'CASCADE'
        );

        // creates index for column `term_id`
        $this->createIndex(
            'idx-cms_relationship-term_id',
            $this->tableName,
            'term_id'
        );

        // add foreign key for table `term`
        $this->addForeignKey(
            'fk-cms_relationship-term_id',
            $this->tableName,
            'term_id',
            $this->termTable,
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `post`
        $this->dropForeignKey(
            'fk-cms_relationship-post_id',
            $this->tableName
        );

        // drops index for column `post_id`
        $this->dropIndex(
            'idx-cms_relationship-post_id',
            $this->tableName
        );

        // drops foreign key for table `term`
        $this->dropForeignKey(
            'fk-cms_relationship-term_id',
            $this->tableName
        );

        // drops index for column `term_id`
        $this->dropIndex(
            'idx-cms_relationship-term_id',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}
