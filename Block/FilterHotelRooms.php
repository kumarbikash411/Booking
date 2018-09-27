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

class FilterHotelRooms extends Template
{  
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->_objectManager = $objectManager;
        $this->_timeZone = $timezone;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
      
    }

    public function getCurrentProduct()
    {
        $product = $this->_coreRegistry->registry('current_product');
        return $product; 
    }


    /* get hotel booking product*/ 
    public function getHotelProduct()
    {
        $array = [];
        $productdata = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($this->getCurrentProduct()->getId());
        
        /** Apply filters here */
        // $collection = $productCollection->create()
        //                                 ->addFieldToFilter('entity_id',$this->getCurrentProduct()->getId());
        // foreach ($collection as $data) {
        //     $data['product_url'] = $data->getProductUrl();
        //     $data['image'] = $data->getThumbnail();
        //     $array[] = $data->getData();
        // } 
        return $productdata;
    }
}
