<?php

use yii\db\Migration;

/**
 * Handles the creation for table `site_config`.
 */
class m161110_133640_create_site_config_table extends Migration
{
    public $tableName;

    public function init()
    {
        parent::init();
        $this->tableName = '{{%site_config}}';
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
            'config_name' => $this->string()->notNull(),
            'config_value' => $this->text(),
            'autoload' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        // creates index for column `config_name`
        $this->createIndex(
            'idx-site_config-config_name',
            $this->tableName,
            'config_name'
        );

        $this->insert($this->tableName, [
            'config_name' => '_site_name',
            'config_value' => 'Yii CMS Application',
            'autoload' => 1,
        ]);

        $this->insert($this->tableName, [
            'config_name' => '_site_keywords',
            'config_value' => '',
            'autoload' => 1,
        ]);

        $this->insert($this->tableName, [
            'config_name' => '_site_description',
            'config_value' => '',
            'autoload' => 1,
        ]);

        $this->insert($this->tableName, [
            'config_name' => '_site_copyright',
            'config_value' => '',
            'autoload' => 1,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops index for column `config_name`
        $this->dropIndex(
            'idx-site_config-config_name',
            $this->tableName
        );
        
        $this->dropTable($this->tableName);
    }
}
