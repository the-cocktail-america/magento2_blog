<?php

namespace TCK\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface {

    protected $_categoryFactory;
    protected $_logger;
    protected $_installer;

    public function __construct(\TCK\Blog\Model\CategoryFactory $categoryFactory, \Psr\Log\LoggerInterface $logger) {
        $this->_categoryFactory = $categoryFactory;
        $this->_logger = $logger;
    }

    /**
     * Installs DB schema for a module
     * select * from setup_module;
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {

        $this->_installer = $setup;

        $this->_installer->startSetup();

        /**
         * Version 1.0.1
         * Adding Categories
         */
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->CategoryUpgrade();
        }

        /**
         * Version 1.0.2
         * Adding Tags
         */
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->TagsUpgrade();
        }

        $this->_installer->endSetup();
    }

    public function CategoryUpgrade() {
        /**
         * Creacion de la tabla de categorias
         */
        $table = $this->_installer->getConnection()
                ->newTable($this->_installer->getTable('tck_blog_category'))
                ->addColumn(
                        'category_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Category ID'
                )
                ->addColumn('slug', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
                ->addColumn('category', Table::TYPE_TEXT, 255, ['nullable' => false], 'Category Title')
                ->addColumn('parent_category', Table::TYPE_SMALLINT, null, ['nullable' => true], 'Parent Category for Subcategories')
                ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Category Active?')
                ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
                ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
                ->addIndex($this->_installer->getIdxName('tck_blog_category', ['slug']), ['slug'])
                ->setComment('Category table');

        $this->_installer->getConnection()->createTable($table);

        /**
         * Creacion de la relacion post y categoria
         */
        $post_cat_relation = $this->_installer->getConnection()
                ->newTable($this->_installer->getTable('tck_post_category'))
                ->addColumn(
                        'postcategory_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Post Category ID'
                )
                ->addColumn('post_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Post ID')
                ->addColumn('category_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Category ID')
                ->setComment('Post-Category Relation');

        $this->_installer->getConnection()->createTable($post_cat_relation);

        /**
         * Insertamos la categoria default 'Uncategorized'
         */
        $modeldata = [
            ['slug' => 'ninguno',
                'category' => 'Ninguno',
                'is_active' => '1'],
            ['slug' => 'uncategorized',
                'category' => 'Uncategorized',
                'is_active' => '1',
                'parent_category' => '1']
        ];

        foreach ($modeldata as $data) {
            $category = $this->_categoryFactory->create();
            $category->addData($data)->save();
        }


        /**
         * Creacion de llaves foraneas
         */
        $postcat_table = $this->_installer->getTable('tck_post_category');
        $post_table = $this->_installer->getTable('tck_blog_post');
        $category_table = $this->_installer->getTable('tck_blog_category');

        if ($this->_installer->getConnection()->isTableExists($postcat_table)) {

            $this->_installer->getConnection()
                    ->addForeignKey($this->_installer->getFkName('post_category', 'post_id', 'blog_post', 'post_id'), $postcat_table, 'post_id', $post_table, 'post_id', Table::ACTION_CASCADE);

            $this->_installer->getConnection()
                    ->addForeignKey($this->_installer->getFkName('post_category', 'category_id', 'blog_category', 'category_id'), $postcat_table, 'category_id', $category_table, 'category_id', Table::ACTION_CASCADE);
        }

        if ($this->_installer->getConnection()->isTableExists($category_table)) {
            $this->_installer->getConnection()
                    ->addForeignKey(
                            $this->_installer->getFkName('blog_category', 'parent_category', 'blog_category', 'category_id'), $category_table, 'parent_category', $category_table, 'category_id', Table::ACTION_CASCADE);
        }
    }

    public function TagsUpgrade() {

        /**
         * Creacion de la tabla de Tags
         */
        $table = $this->_installer->getConnection()
                ->newTable($this->_installer->getTable('tck_blog_tags'))
                ->addColumn(
                        'tags_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Tags ID'
                )
                ->addColumn('slug', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
                ->addColumn('tag', Table::TYPE_TEXT, 255, ['nullable' => false], 'Tag Title')
                ->addColumn('description', Table::TYPE_TEXT, null, ['nullable' => true], 'Tag description')
                ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Tag Active?')
                ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
                ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
                ->addIndex($this->_installer->getIdxName('tck_blog_tags', ['slug']), ['slug'])
                ->setComment('Tags table');

        $this->_installer->getConnection()->createTable($table);

        /**
         * Creacion de la relacion post y tag
         */
        $post_tag_relation = $this->_installer->getConnection()
                ->newTable($this->_installer->getTable('tck_post_tags'))
                ->addColumn(
                        'posttags_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Post Tags ID'
                )
                ->addColumn('post_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Post ID')
                ->addColumn('tags_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Tags ID')
                ->setComment('Post-Tags Relation');

        $this->_installer->getConnection()->createTable($post_tag_relation);

        /**
         * Creacion de llaves foraneas
         */
        $posttag_table = $this->_installer->getTable('tck_post_tags');
        $post_table = $this->_installer->getTable('tck_blog_post');
        $tag_table = $this->_installer->getTable('tck_blog_tags');

        if ($this->_installer->getConnection()->isTableExists($posttag_table)) {

            $this->_installer->getConnection()
                    ->addForeignKey($this->_installer->getFkName('post_tags', 'post_id', 'blog_post', 'post_id'), $posttag_table, 'post_id', $post_table, 'post_id', Table::ACTION_CASCADE);

            $this->_installer->getConnection()
                    ->addForeignKey($this->_installer->getFkName('post_tags', 'tags_id', 'blog_tags', 'tags_id'), $posttag_table, 'tags_id', $tag_table, 'tags_id', Table::ACTION_CASCADE);
        }

        /**
         * Agregar columna de descripcion a tabla de categorias
         */
        $this->_installer->getConnection()
                ->addColumn($this->_installer->getTable('tck_blog_category'), 'description', ['type' => Table::TYPE_TEXT, 'nullable' => null, 'comment' => 'Category description']);
    }

}
