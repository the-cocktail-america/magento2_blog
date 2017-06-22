<?php
namespace TCK\Blog\Block\Adminhtml\Tags\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_tags_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Información del Tag'));
    }
}