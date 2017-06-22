<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {

        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_objectManager->create('TCK\Blog\Model\Tags');

            $id = $this->getRequest()->getParam('tags_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('Tag guardado exitosamente.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('tags_id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Ocurrió un error al guardar el tag.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('tags_id' => $this->getRequest()->getParam('tags_id')));
            return;
        }
        $this->_redirect('*/*/');
    }

}
