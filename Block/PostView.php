<?php
namespace TCK\Blog\Block;

class PostView extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{

    protected $_helper;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \TCK\Blog\Model\Post $post
     * @param \TCK\Blog\Model\PostFactory $postFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \TCK\Blog\Model\Post $post,
        \TCK\Blog\Model\PostFactory $postFactory,
        \TCK\Blog\Helper\Data $helper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_postFactory = $postFactory;
        $this->_helper = $helper;
    }

    /**
     * @return \TCK\Blog\Model\Post
     */
    public function getPost()
    {
        // Check if posts has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'posts' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                /** @var \TCK\Blog\Model\Post $page */
                $post = $this->_postFactory->create();
            } else {
                $post = $this->_post;

                // Parse URL images
                // Maybe there are a better way to fix the urls.
                $post_content = $post->getContent();
                $r_start = '{{media url="';
                $rr_start ='/pub/media/';
                $r_end = '"}}';
                $rr_end ='';
                $post_content = str_replace($r_start, $rr_start, $post_content);
                $post_content = str_replace($r_end, $rr_end, $post_content);

                $post->setContent($post_content);

            }
            $this->setData('post', $post);

            // Get post settings
            $_config = $this->_helper->getConfig('posts');
            $shareableFacebook = $_config['share']['facebook'];
            $shareableTwitter= $_config['share']['twitter'];
            $shareableGoogleplus = $_config['share']['googleplus'];

            $post->setShareableFacebook($shareableFacebook);
            $post->setShareableTwitter($shareableTwitter);
            $post->setShareableGoogleplus($shareableGoogleplus);
        }
        return $this->getData('post');
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
        $post = $this->getPost();
        parent::_prepareLayout();

        // Get main settings
        $_config = $this->_helper->getConfig('main');
        $blog_title = (isset($_config['general']['title'])) ? $_config['general']['title'] : "Blog";
        $blog_url = (isset($_config['general']['url_key'])) ? $_config['general']['url_key'] : "blog";

        // Get SEO settings
        $title = __($post->getTitle());
        $seo_description = __($post->getSeoDescription());
        $seo_keywords = __($post->getSeoKeywords());

        // Set config page
        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->setDescription($seo_description);
        $this->pageConfig->setKeywords($seo_keywords);

        // Page title
        /*$pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }*/

        // Construct breadscrumb
        $this->getLayout()->createBlock('Magento\Catalog\Block\Breadcrumbs');
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'index',
                [
                    'label' => $blog_title,
                    'title' => $blog_title,
                    'link' => "/".$blog_url."/"
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'post',
                [
                    'label' => $title,
                    'title' => $title,
                    'link' => false
                ]
            );
        }

        return $this;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\TCK\Blog\Model\Post::CACHE_TAG . '_' . $this->getPost()->getId()];
    }

}
