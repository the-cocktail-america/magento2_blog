<?php
namespace TCK\Blog\Model\ResourceModel;

/**
 * Blog post mysql resource
 */
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tck_blog_post', 'post_id');
    }

    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        if (!$this->isValidPostIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('La URL contiene caracteres no validos.')
            );
        }

        if ($this->isNumericPostIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('La URL no puede contener solo numeros.')
            );
        }

        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Ashsmith\Blog\Model\Post $object
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {

            $select->where(
                'is_active = ?',
                1
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier and activity
     *
     * @param string $identifier
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['bp' => $this->getMainTable()]
        )->where(
            'bp.identifier = ?',
            $identifier
        );

        if (!is_null($isActive)) {
            $select->where('bp.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     *  Check whether post identifier is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericPostIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether post identifier is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidPostIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     * Check if post identifier exists
     * return post id if post exists
     *
     * @param string $identifier
     * @return int
     */
    public function checkIdentifier($identifier)
    {
        $select = $this->_getLoadByIdentifierSelect($identifier, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('bp.post_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
