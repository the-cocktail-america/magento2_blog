<?php
namespace TCK\Blog\Helper;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_categoryFactory;
    
    protected $_logger;
    
    
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \TCK\Blog\Model\CategoryFactory $categoryFactory,
        \Psr\Log\LoggerInterface $logger
        
    ) {
        $this->_logger = $logger;
        $this->_categoryFactory = $categoryFactory;
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        
        parent::__construct($context);
    }
    
    public function getCategories($posts = false){
        
        $categories = array();
        $categoryFactory = $this->_categoryFactory->create();
        $collection = $categoryFactory->getCollection();
        if($posts){
            $collection->getSelect()
                    ->joinLeft(
                           ['pc' => $collection->getTable('tck_blog_category')],
                            'main_table.parent_category = pc.category_id',
                            array('pc.category as parent_cat')
                            )
                    ->where('main_table.parent_category IS NOT NULL')
                    ->order(array('pc.category', 'main_table.category'));

            foreach($collection as $data){
                $categories[$data->getParentCat()][] = ['value' => $data->getId(), 'label' => $data->getCategory()];
            }
        }
        else{
            $collection->getSelect()->order('main_table.category');
            foreach($collection as $data){
                $categories[] = ['value' => $data->getId(), 'label' => $data->getCategory()];
            }
        }
        
        return $categories;
           
    }
    
    public function getCategoryFormatted($posts = false){
        
        $result = array();
        $categories = $this->getCategories($posts);
        
        if($posts){
            foreach($categories as $key => $data){
                $result[] = ['label' => $key, 'value' => $data];
            }
        }else{
            $result = $categories;
        }

        return $result;
        
    }
    
    
}
