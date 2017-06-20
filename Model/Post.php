<?php namespace TCK\Blog\Model;

use TCK\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Post  extends \Magento\Framework\Model\AbstractModel implements PostInterface, IdentityInterface
{

    /**#@+
     * Status de post
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'blog_post';

    /**
     * @var string
     */
    protected $_cacheTag = 'blog_post';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'blog_post';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    protected $_shareableFacebook = false;
    protected $_shareableTwitter = false;
    protected $_shareableGoogleplus = false;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $data
     */
    function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TCK\Blog\Model\ResourceModel\Post');
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
        return $this->_getResource()->checkIdentifier($identifier);
    }

    /**
     * Prepare post's statuses.
     * Available event blog_post_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::POST_ID);
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Return the desired URL of a post
     *  eg: /blog/view/index/id/1/
     * @TODO Move to a PostUrl model, and make use of the
     * @TODO rewrite system, using identifier to build url.
     * @TODO desired url: /blog/my-latest-blog-post-title
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_urlBuilder->getUrl('noticias/' . $this->getIdentifier());
    }

    /**
     * Get SEO keywords
     *
     * @return string|null
     */
    public function getSeoKeywords()
    {
        return $this->getData(self::SEO_KEYWORDS);
    }

    /**
     * Get SEO description
     *
     * @return string|null
     */
    public function getSeoDescription()
    {
        return $this->getData(self::SEO_DESCRIPTION);
    }


    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get summary
     *
     * @return string|null
     */
    public function getSummary()
    {
        return $this->getData(self::SUMMARY);
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setId($id)
    {
        return $this->setData(self::POST_ID, $id);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Set SEO keywords
     *
     * @param string $seo_keywords
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSeoKeywords($seo_keywords)
    {
        return $this->setData(self::SEO_KEYWORDS, $seo_keywords);
    }

    /**
     * Set SEO description
     *
     * @param string $seo_description
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSeoDescription($seo_description)
    {
        return $this->setData(self::SEO_DESCRIPTION, $seo_description);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSummary($summary)
    {
        return $this->setData(self::SUMMARY, $summary);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set creation time
     *
     * @param string $creation_time
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setCreationTime($creation_time)
    {
        return $this->setData(self::CREATION_TIME, $creation_time);
    }

    /**
     * Set update time
     *
     * @param string $update_time
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME, $update_time);
    }

    /**
     * Set is active
     *
     * @param int|bool $is_active
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

    /**
     *
     */
    public function setShareableFacebook($shareableFacebook) {
      $this->_shareableFacebook = $shareableFacebook;
    }

    /**
     *
     */
    public function setShareableTwitter($shareableTwitter) {
      $this->_shareableTwitter = $shareableTwitter;
    }

    /**
     *
     */
    public function setShareableGoogleplus($shareableGoogleplus) {
      $this->_shareableGoogleplus = $shareableGoogleplus;
    }

    /**
     *
     */
    public function isShareableFacebook() {
      return $this->_shareableFacebook;
    }

    /**
     *
     */
    public function isShareableTwitter() {
      return $this->_shareableTwitter;
    }

    /**
     *
     */
    public function isShareableGoogleplus() {
      return $this->_shareableGoogleplus;
    }


    public function isShareable() {
      return $this->isShareableFacebook() || $this->isShareableTwitter() || $this->isShareableGoogleplus();
    }

}
