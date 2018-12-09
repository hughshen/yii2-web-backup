<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cms_term`.
 */
class m170622_151837_create_cms_term_table extends Migration
{
    public $tableName;

    public function init()
    {
        parent::init();
        $this->tableName = '{{%cms_term}}';
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
            'parent' => $this->integer()->defaultValue(0),
            'taxonomy' => $this->string(32)->notNull()->defaultValue('category'),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'slug' => $this->string()->notNull(),
            'sorting' => $this->integer()->notNull()->defaultValue(0),
            'extra_data' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `taxonomy`
        $this->createIndex(
            'idx-cms_term-taxonomy',
            $this->tableName,
            'taxonomy'
        );

        // creates index for column `slug`
        $this->createIndex(
            'idx-cms_term-slug',
            $this->tableName,
            'slug'
        );

        // creates index for column `created_at`
        $this->createIndex(
            'idx-cms_term-created_at',
            $this->tableName,
            'created_at'
        );

        $this->insert($this->tableName, [
            'taxonomy' => 'category',
            'name' => 'Test Category',
            'slug' => 'test-category',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'taxonomy' => 'tag',
            'name' => 'Test Tag',
            'slug' => 'test-tag',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'taxonomy' => 'group',
            'name' => 'Header Menu',
            'slug' => 'header-menu',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'taxonomy' => 'group',
            'name' => 'Footer Menu',
            'slug' => 'footer-menu',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops index for column `taxonomy`
        $this->dropIndex(
            'idx-cms_term-taxonomy',
            $this->tableName
        );

        // drops index for column `slug`
        $this->dropIndex(
            'idx-cms_term-slug',
            $this->tableName
        );

        // drops index for column `created_at`
        $this->dropIndex(
            'idx-cms_term-created_at',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}
