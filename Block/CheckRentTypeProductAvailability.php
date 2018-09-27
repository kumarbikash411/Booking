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
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data;

class CheckRentTypeProductAvailability extends Template
{    
    /**
    * 
    * @param \Magento\Framework\View\Element\Template\Context $context
    * @param \Magento\Framework\Registry $coreRegistry
    * @param array $data
    */
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        Timezone $timezone,
        Data $price,
    	array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_timeZone = $timezone;
        $this->_price = $price;       
    }
    
    public function getCurrencySymbol()
    {
        return $this->_price;
    }

    public function getProductId()
    {
        $id = $this->getRequest()->getParam('product_id');
        return $id;
    }


    public function getProduct()
    {
        $productData = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($this->getProductId());
        return $productData;
    }
}
