<?php
namespace TCK\Blog\Block\Adminhtml;
class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_category';/*block grid.php directory*/
        $this->_blockGroup = 'TCK_Blog';
        $this->_headerText = __('Categoria');
        $this->_addButtonLabel = __('Nueva Categoria'); 
        parent::_construct();
		
    }
}
