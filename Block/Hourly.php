<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the 
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
  * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Booking
 * @author   	CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block;

use Magento\Framework\View\Element\Template;

class Hourly extends Template
{  
	
   protected $_category;
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Booking\Helper\Data $helperdata
    )
    {
        $this->_objectManager = $objectManager;
        $this->_timeZone = $timezone;
        $this->_helper = $helperdata;
        parent::__construct($context);
      
    }

    /* get hotel banner from config */

    public function getConfigBanner()
    {
        $configvalue = $this->_helper->getStoreConfigValue('booking/banner_setting/hourly_banner');
        // $hotelBanner = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
        //                  ->getStore()
        //                  ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'booking/store/banner/'.$configvalue;
        return $configvalue;
    }
    
}
