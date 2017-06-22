<?php
namespace TCK\Blog\Block\Adminhtml\Post\Edit;

/**
 * Adminhtml blog post edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    
    protected $_catHelper;
    
    protected $_urlBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \TCK\Blog\Helper\Category $cathelper,
        \Magento\Backend\Model\UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_catHelper = $cathelper;
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('post_form');
        $this->setTitle(__('Información de la entrada'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \TCK\Blog\Model\Post $model */
        $model = $this->_coreRegistry->registry('blog_post');
        $collection = $model->getCollection();
        $collection->getSelect()
                ->joinLeft(
                        ['pc' => $collection->getTable('tck_post_category')],
                        'main_table.post_id = pc.post_id',
                        array('group_concat(pc.category_id separator ",") as category')
                        )
                ->where('main_table.post_id = ?', $this->getRequest()->getParam("post_id"))
                ->group('main_table.post_id');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Información'), 'class' => 'fieldset-wide']
        );

        if ($model->getPostId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Título'), 'title' => __('Título'), 'required' => true]
        );

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
                'required' => true,
                'class' => 'validate-xml-identifier'
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Estatus'),
                'title' => __('Estatus'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Habilitada'), '0' => __('Deshabilitada')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $fieldset->addField(
            'summary',
            'editor',
            [
                'name' => 'summary',
                'label' => __('Resumen'),
                'title' => __('Resumen'),
                'style' => 'height:5em',
                'required' => true
                //'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Contenido'),
                'title' => __('Contenido'),
                'style' => 'height:36em',
                'required' => true,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'seo_keywords',
            'text',
            ['name' => 'seo_keywords', 'label' => __('SEO Palabras Clave'), 'title' => __('SEO Palabras Clave'), 'required' => true]
        );

        $fieldset->addField(
            'seo_description',
            'text',
            ['name' => 'seo_description', 'label' => __('SEO Descripción'), 'title' => __('SEO Descripción'), 'required' => true]
        );
        
        $fieldset->addField(
            'category',
            'multiselect',
            [
                'name' => 'category',
                'label' => __('Categoría'),
                'title' => __('Categoría'),
                'required' => false,
                'values' => $this->_catHelper->getCategoryFormatted(true),
                
            ]
        );
        
        $fieldset->addField(
            'tags',
            'text',
            [
                'name' => 'tags',
                'label' => __('Tags'),
                'title' => __('Tags'),
                'required' => false,
                
            ]
        );
        
        $fieldset->addField(
            'selected_tags',
            'multiselect',
                [
                    'name' => 'selected_tags',
                    'label' => false,
                    'title' => false,
                    'required' => false,
                ]
        );
        
        $form->getElement('tags')->setAfterElementJs(
                $this->js()
                );
        
        $data = ($collection->getSize() > 0) ? $collection->getData()[0] : "";
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    
    public function js(){
        $url = $this->_urlBuilder->getUrl('blog/tags/ajaxtags');
        $js = <<<EOF
        <script type='text/javascript'>
            require(['jquery', 'jquery/ui'], function($){
                $(function(){
                    function populateTagMultiselect(object){
                        $("#post_selected_tags")
                            .append($("<option></option>")
                            .val(object.item.id)
                            .html(object.item.label));
                        $("#post_selected_tags option").prop('selected',true);
                        
                    }
                
                    $("#post_tags").autocomplete({
                        source: "{$url}",
                        minLength: 3,
                        select: function( event, ui ) {
                           populateTagMultiselect(ui);
                           this.value = "";
                           return false;
                        }
                    });
                });
            });
        </script>
EOF;
          return $js;      
        
    }
}
