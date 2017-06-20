<?php namespace TCK\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    protected $_categoryFactory;
    protected $_logger;
    
    public function __construct(\TCK\Blog\Model\CategoryFactory $categoryFactory,
    \Psr\Log\LoggerInterface $logger) {
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
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Creacion de la tabla de categorias
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('tck_blog_category'))
            ->addColumn(
                'category_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Category ID'
            )
            ->addColumn('slug', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
            ->addColumn('category', Table::TYPE_TEXT, 255, ['nullable' => false], 'Category Title')
            ->addColumn('parent_category', Table::TYPE_SMALLINT, null, ['nullable' => true], 'Parent Category for Subcategories')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Category Active?')
            ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
            ->addIndex($installer->getIdxName('tck_blog_category', ['slug']), ['slug'])
            ->setComment('Category table');

        $installer->getConnection()->createTable($table);

        /**
         * Creacion de la relacion post y categoria
         */
        $post_cat_relation = $installer->getConnection()
                ->newTable($installer->getTable('tck_post_category'))
                ->addColumn(
                    'postcategory_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Post Category ID'
                )
                ->addColumn('post_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Post ID')
                ->addColumn('category_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Category ID')
                ->setComment('Post-Category Relation');
        
        $installer->getConnection()->createTable($post_cat_relation);

        /**
         * Insertamos la categoria default 'Uncategorized'
         */
        $data = ['slug' => 'uncategorized',
                 'category' => 'Uncategorized',
                 'is_active' => '1'];
        
        $category = $this->_categoryFactory->create();
        $category->addData($data)->save();
        
        
        /**
         * Creacion de llaves foraneas
         */
        $postcat_table = $installer->getTable('tck_post_category');
        $post_table = $installer->getTable('tck_blog_post');
        $category_table = $installer->getTable('tck_blog_category');

        if($installer->getConnection()->isTableExists($postcat_table)){
            
            $installer->getConnection()
                    ->addForeignKey($installer->getFkName('post_category', 'post_id', 'blog_post', 'post_id'), 
                            $postcat_table, 
                            'post_id', 
                            $post_table, 
                            'post_id',
                            Table::ACTION_CASCADE);
            
            $installer->getConnection()        
                    ->addForeignKey($installer->getFkName('post_category', 'category_id', 'blog_category', 'category_id'), 
                            $postcat_table, 
                            'category_id', 
                            $category_table, 
                            'category_id',
                            Table::ACTION_CASCADE);
            
        }
        
        if($installer->getConnection()->isTableExists($category_table)){
            $installer->getConnection()
                    ->addForeignKey(
                            $installer->getFkName('blog_category', 'parent_category', 'blog_category', 'category_id'), 
                            $category_table, 
                            'parent_category', 
                            $category_table, 
                            'category_id', 
                            Table::ACTION_CASCADE);
        }
        
        $installer->endSetup();
    }

}
