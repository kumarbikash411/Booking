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
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block;

use Magento\Framework\View\Element\Template;

class Daily extends Template
{  
    
   protected $_category;
    
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Booking\Helper\Data $helerdata,
        array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_objectManager = $objectManager;
        $this->_timeZone = $timezone;
        $this->_helper = $helerdata;
    }

    /* get hotel banner from config */

    public function getConfigBanner()
    {
        $configvalue = $this->_helper->getStoreConfigValue('booking/banner_setting/daily_banner');
        return $configvalue;
    }
    
}
