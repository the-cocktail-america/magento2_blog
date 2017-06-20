<?php
namespace TCK\Blog\Controller\Adminhtml\Category;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		
		 $ids = $this->getRequest()->getParam('id');
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Por favor seleccione por lo menos una categoria.'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_objectManager->get('TCK\Blog\Model\Category')->load($id);
					$row->delete();
				}
                $this->messageManager->addSuccess(
                    __('%1 categoria(s) eliminada(s).', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
		 $this->_redirect('*/*/');
    }
}
