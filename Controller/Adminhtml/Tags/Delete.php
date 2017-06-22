<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

class Delete extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {
        $id = $this->getRequest()->getParam('tags_id');
        try {
            $banner = $this->_objectManager->get('TCK\Blog\Model\Tags')->load($id);
            $banner->delete();
            $this->messageManager->addSuccess(
                    __('Tag eliminado exitosamente!')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

}
