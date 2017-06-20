<?php
namespace TCK\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{

    protected $_resource;
    /**
     * @param Action\Context $context
     */
    
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource
        )
    {
        $this->_resource = $resource;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TCK_Blog::save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \TCK\Blog\Model\Post $model */
            $model = $this->_objectManager->create('TCK\Blog\Model\Post');

            $id = $this->getRequest()->getParam('post_id');
            if ($id) {
                $model->load($id);
            }
            
            $model->setData($data);

            $this->_eventManager->dispatch(
                'blog_post_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();

                $this->deletePostCategory($model->getId());
                
                foreach($data['category'] as $category){
                    $this->savePostCategories($model->getId(), $category);
                }
                
                $this->messageManager->addSuccess(__('Has guardado la entrada.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, $e->getMessage());//__('Algo fallo al guardar la entrada.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    
    public function savePostCategories($post, $category){
        
        $postCategoryResource = $this->_objectManager->create('TCK\Blog\Model\PostCategory');

        $postCategoryResource->setPostId($post);
        $postCategoryResource->setCategoryId($category);
        $postCategoryResource->save();
           
    }
    
    public function deletePostCategory($post){
        
        $postCategoryResource = $this->_objectManager->create('TCK\Blog\Model\PostCategory');
        
        $collection = $postCategoryResource->getCollection();
        $collection->addFilter('post_id', $post)
                ->walk('delete');
        
    }
    
}