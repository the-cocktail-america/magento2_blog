<?php


namespace TCK\Blog\Model\ResourceModel\PostCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'TCK\Blog\Model\PostCategory',
            'TCK\Blog\Model\ResourceModel\PostCategory'
        );
    }
}
