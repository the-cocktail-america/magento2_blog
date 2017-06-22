<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

class Edit extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {


        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('tags_id');

        $model = $this->_objectManager->create('TCK\Blog\Model\Tags');

        $registryObject = $this->_objectManager->get('Magento\Framework\Registry');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('El tag no existe.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject->register('tck_blog_tags', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

}
