<?php

namespace TCK\Blog\Model\Category\Source;

class ParentCategory implements \Magento\Framework\Option\ArrayInterface
{
    protected $_helper;
    
    public function __construct(
            \TCK\Blog\Helper\Data $helper) {
        $this->_helper = $helper;
    }

    public function toOptionArray()
    {     
        return $this->_helper->getCategoryOptionArray();
    }

    public function toArray()
    {
        return ['' => __('')];
    }
}