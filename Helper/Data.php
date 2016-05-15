<?php
namespace TCK\Blog\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_objectManager;

    /**
     *
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        parent::__construct($context);
    }

    /**
     * Get module configuration
     * @param String $config_path
     * @return Array Module configuration
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Format a date.
     * @param date $date
     * @return String formatted date
     * @TODO Translate months
     */
    public function parseDate($date)
    {
        $date = explode("-", $date);
        $day = explode(" ", $date[2]);
        $months = array(__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December'));
        $month = intval($date[1])-1;
        $date = $day[0]." ".$months[$month]." ".$date[0];
        return $date;
    }
}
