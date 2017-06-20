<?php
namespace TCK\Blog\Controller\Adminhtml\Category;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		$id = $this->getRequest()->getParam('id');
		try {
				$banner = $this->_objectManager->get('TCK\Blog\Model\Category')->load($id);
				$banner->delete();
                $this->messageManager->addSuccess(
                    __('Categoria eliminada exitosamente!')
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
	    $this->_redirect('*/*/');
    }
}
