<?php

namespace TCK\Blog\Model\ResourceModel;

class PostCategory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tck_post_category', 'postcategory_id');
    }
}
