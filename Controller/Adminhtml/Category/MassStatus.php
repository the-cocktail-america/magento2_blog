<?php
namespace TCK\Category\Controller\Adminhtml\Category;

class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		 $ids = $this->getRequest()->getParam('id');
		 $status = $this->getRequest()->getParam('status');
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Por favor seleccione por lo menos una categoria.'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_objectManager->get('TCK\Firstgrid\Model\Category')->load($id);
					$row->setData('status',$status)
							->save();
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
