<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

class MassDelete extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {

        $ids = $this->getRequest()->getParam('tags_id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Por favor seleccione por lo menos un tag.'));
        } else {
            try {
                foreach ($ids as $id) {
                    $row = $this->_objectManager->get('TCK\Blog\Model\Tags')->load($id);
                    $row->delete();
                }
                $this->messageManager->addSuccess(
                        __('%1 Tag eliminad(s).', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

}
