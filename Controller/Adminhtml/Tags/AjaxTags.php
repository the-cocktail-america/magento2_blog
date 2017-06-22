<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

class AjaxTags extends \Magento\Backend\App\Action{
    
    protected $_jsonHelper;
    protected $_resultFactory;
    protected $_tagFactory;
    
    public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\Json\Helper\Data $jsonHelper,
            \Magento\Framework\View\Result\PageFactory $resultFactory,
            \TCK\Blog\Model\TagsFactory $tagFactory
    ){
        $this->_jsonHelper = $jsonHelper;
        $this->_tagFactory = $tagFactory;
        parent::__construct($context);
    }
    
    public function execute(){

        $result = array();
        $term = $this->getRequest()->getParam("term");
        $collection = $this->_tagFactory->create()->getCollection()
                ->addFieldToFilter('tag', array('like' => "%".$term."%"));
        
        foreach($collection as $data){
            $result[] = ['id' => $data->getId(), 'label' => $data->getTag(), 'value' => $data->getTag()];
        }
        
        $response = $this->_jsonHelper->jsonEncode(
            $result
        );
        
        echo $response;
        
    }
}
