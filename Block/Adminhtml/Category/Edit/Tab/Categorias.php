<?php

namespace TCK\Blog\Block\Adminhtml\Category\Edit\Tab;

class Categorias extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    
    protected $_catHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, 
            \Magento\Framework\Registry $registry, 
            \Magento\Framework\Data\FormFactory $formFactory, 
            \Magento\Store\Model\System\Store $systemStore, 
            \TCK\Blog\Helper\Category $cathelper,
            array $data = array()
    ) {
        $this->_catHelper = $cathelper;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm() {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('tck_blog_category');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Categorias')));

        if ($model->getId()) {
            $fieldset->addField('category_id', 'hidden', array('name' => 'id'));
        }

        $fieldset->addField(
            'category', 'text', array(
            'name' => 'category',
            'label' => __('Categoria'),
            'title' => __('Categoria'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'slug', 'text', array(
            'name' => 'slug',
            'label' => __('Slug'),
            'title' => __('Slug'),
            'required' => true,
                )
        );
        $fieldset->addField(
            'parent_category', 'select', array(
            'name' => 'parent_category',
            'label' => __('Categoria Padre'),
            'title' => __('Categoria Padre'),
            'values' => $this->_catHelper->getCategoryFormatted(),
            'required' => true,
                )
        );
        /* {{CedAddFormField}} */

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return __('Categorias');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Categorias');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }

}
