<?php

namespace TCK\Blog\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action;

class NewAction extends \Magento\Backend\App\Action {

    public function execute() {
        $this->_forward('edit');
    }

}
