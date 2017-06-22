<?php

namespace TCK\Blog\Model\ResourceModel;

class PostTags extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tck_post_tags', 'posttags_id');
    }
}
