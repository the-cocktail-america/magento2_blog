<?php

namespace TCK\Category\Controller\Adminhtml\Tags;

class MassStatus extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {
        $ids = $this->getRequest()->getParam('tags_id');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Por favor seleccione por lo menos un tag.'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_objectManager->get('TCK\Firstgrid\Model\Tags')->load($id);
                    $row->setData('status', $status)
                            ->save();
                }
                $this->messageManager->addSuccess(
                        __('%1 tag eliminado(s).', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

}
