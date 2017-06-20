<?php
namespace TCK\Blog\Block\Adminhtml\Category;

/**
 * CMS block edit form container
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected function _construct()
    {
	$this->_objectId = 'category_id';
        $this->_blockGroup = 'TCK_Blog';
        $this->_controller = 'adminhtml_category';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Guardar'));
        $this->buttonList->update('delete', 'label', __('Eliminar'));

        $this->buttonList->add(
            'saveandcontinue',
            array(
                'label' => __('Guardar y continuar editando'),
                'class' => 'save',
                'data_attribute' => array(
                    'mage-init' => array('button' => array('event' => 'saveAndContinueEdit', 'target' => '#edit_form'))
                )
            ),
            -100
        );

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'hello_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'hello_content');
                }
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('checkmodule_checkmodel')->getId()) {
            return __("Editar Registro '%1'", $this->escapeHtml($this->_coreRegistry->registry('checkmodule_checkmodel')->getTitle()));
        } else {
            return __('Nuevo Registro');
        }
    }
}