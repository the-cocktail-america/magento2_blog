<?php


namespace TCK\Blog\Model\ResourceModel\PostTags;

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
            'TCK\Blog\Model\PostTags',
            'TCK\Blog\Model\ResourceModel\PostTags'
        );
    }
}
