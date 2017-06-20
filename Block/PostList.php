<?php
/**
 * PostList Block
 *
 * @var $block \TCK\Blog\Block\PostList
 */

namespace TCK\Blog\Block;

use TCK\Blog\Api\Data\PostInterface;
use TCK\Blog\Model\ResourceModel\Post\Collection as PostCollection;

class PostList extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{

    /**
     * @var \TCK\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    protected $_helper;
    
    protected $_logger;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \TCK\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \TCK\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \TCK\Blog\Helper\Data $helper,
 \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_helper = $helper;
        $this->_logger = $logger;
    }


    /**
     * @return \TCK\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPosts()
    {
        //Pagination parameters
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 1;
        
        // Check if posts has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'posts' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('posts')) {
            $posts = $this->_postCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addOrder(
                    PostInterface::CREATION_TIME,
                    PostCollection::SORT_ORDER_DESC
                )->setPageSize($pageSize)->setCurPage($page);
            $this->setData('posts', $posts);
        }
        return $this->getData('posts');
    }

    /**
     *
     */
    public function getHelper() {
        return $this->_helper;
    }

    /**
     *
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // Get main settings
        $_config = $this->_helper->getConfig('main');
        $blog_title = (isset($_config['general']['title'])) ? $_config['general']['title'] : "Blog";
        $blog_url = (isset($_config['general']['url_key'])) ? $_config['general']['url_key'] : "blog";

        // Get SEO settings
        $seo_description = (isset($_config['seo']['description'])) ? $_config['seo']['description'] : "";
        $seo_keywords = (isset($_config['seo']['keywords'])) ? $_config['seo']['keywords'] : "";

        // Get post settings
        $_config = $this->_helper->getConfig('posts');
        $share_fckb = $_config['share']['facebook'];
        $share_twtr = $_config['share']['twitter'];
        $share_goop = $_config['share']['googleplus'];

        // Construct breadscrumb
        $this->getLayout()->createBlock('Magento\Catalog\Block\Breadcrumbs');
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'index',
                [
                    'label' => $blog_title,
                    'title' => $blog_title,
                    'link' => false
                ]
            );
        }

        // Page title
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
          $pageMainTitle->setPageTitle($blog_title);
        }

        //Pager
        $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager', 'tck.blog.pager'
                )
                ->setAvailableLimit(
                        array(5=>5, 10=>10, 15=>15)
                        )
                ->setShowPerPage(true)
                ->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->getPosts()->load();
        
        // Set config page
        $this->pageConfig->setDescription($seo_description);
        $this->pageConfig->setKeywords($seo_keywords);
        $this->pageConfig->getTitle()->set($blog_title);

//        return parent::_prepareLayout();
        return $this;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\TCK\Blog\Model\Post::CACHE_TAG . '_' . 'list'];
    }
    
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
