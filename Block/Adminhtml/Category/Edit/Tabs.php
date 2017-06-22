<?php
namespace TCK\Blog\Block\Adminhtml\Category\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Información de la Categoría'));
    }
}