<?php
namespace TCK\Blog\Api\Data;


interface PostInterface
{
    /**
     * Constants.
     */
    const POST_ID           = 'post_id';
    const IDENTIFIER        = 'identifier';
    const SEO_KEYWORDS      = 'seo_keywords';
    const SEO_DESCRIPTION   = 'seo_description';
    const TITLE             = 'title';
    const SUMMARY           = 'summary';
    const CONTENT           = 'content';
    const CREATION_TIME     = 'creation_time';
    const UPDATE_TIME       = 'update_time';
    const IS_ACTIVE         = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get summary
     *
     * @return string|null
     */
    public function getSummary();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get SEO Keywords
     *
     * @return string
     */
    public function getSeoKeywords();


    /**
     * Get SEO Description
     *
     * @return string
     */
    public function getSeoDescription();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setId($id);

    /**
     * Set Identifier
     *
     * @param string $identifier
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setIdentifier($identifier);

    /**
     * Return full URL including base url.
     *
     * @return mixed
     */
    public function getUrl();

    /**
     * Set title
     *
     * @param string $title
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setTitle($title);

    /**
     * Set summary
     *
     * @param string $summary
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSummary($summary);

    /**
     * Set content
     *
     * @param string $content
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setContent($content);

    /**
     * Set SEO Keywords
     *
     * @param string $seo_keywords
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSeoKeywords($seo_keywords);

    /**
     * Set SEO Description
     *
     * @param string $seo_description
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setSeoDescription($seo_description);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \TCK\Blog\Api\Data\PostInterface
     */
    public function setIsActive($isActive);
}
