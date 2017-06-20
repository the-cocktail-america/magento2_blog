<?php

namespace TCK\Blog\Block\Adminhtml\Category;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Magento\Store\Model\WebsiteFactory $websiteFactory, \TCK\Blog\Model\ResourceModel\Category\Collection $collectionFactory, \Magento\Framework\Module\Manager $moduleManager, array $data = []
    ) {

        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }

    /**
     * @return Store
     */
    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection() {
        try {


            $collection = $this->_collectionFactory;
            $collection->join($collection->getTable('tck_blog_category'),
                    'main_table.parent_category = '.$collection->getTable('tck_blog_category').'.category_id',
                    array($collection->getTable('tck_blog_category').'.category as pcategory')
                    );

            $this->setCollection($collection);

            parent::_prepareCollection();

            return $this;
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                        'websites', 'catalog_product_website', 'website_id', 'product_id=entity_id', null, 'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns() {

        $this->addColumn(
                'category', [
            'header' => __('Categoria'),
            'index' => 'category',
            'class' => 'category'
                ]
        );
        $this->addColumn(
                'slug', [
            'header' => __('Slug'),
            'index' => 'slug',
            'class' => 'slug'
                ]
        );
        $this->addColumn(
                'parent_category', [
            'header' => __('categoria Padre'),
            'index' => 'pcategory',
            'class' => 'parent_category'
                ]
        );
        /* {{CedAddGridColumn}} */

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('category_id');
        $this->getMassactionBlock()->setFormFieldName('category_id');

        $this->getMassactionBlock()->addItem(
                'delete', array(
            'label' => __('Eliminar'),
            'url' => $this->getUrl('blog/*/massDelete'),
            'confirm' => __('Estas seguro que deseas eliminar este registro?')
                )
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('blog/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl(
                        'blog/*/edit', ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }

}
