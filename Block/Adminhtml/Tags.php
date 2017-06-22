<?php

namespace TCK\Blog\Block\Adminhtml;

class Tags extends \Magento\Backend\Block\Widget\Grid\Container {

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct() {

        $this->_controller = 'adminhtml_tags'; /* block grid.php directory */
        $this->_blockGroup = 'TCK_Blog';
        $this->_headerText = __('Tags');
        $this->_addButtonLabel = __('Nuevo Tag');
        parent::_construct();
    }

}
