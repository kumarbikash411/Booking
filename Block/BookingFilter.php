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

class BookingFilter extends Template
{  
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_timeZone = $timezone;
        parent::__construct($context);
      
    }

    /* get hotel booking product*/ 
    public function getHotelProduct()
    {
        $array = [];
        $productCollection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        
        /** Apply filters here */
        $collection = $productCollection->create()
                                        ->addAttributeToSelect('*')
                                        ->load();
        foreach ($collection as $data) {
            $data['product_url'] = $data->getProductUrl();
            $data['image'] = $data->getThumbnail();
            $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($data->getAttributeSetId())->getAttributeSetName();
            if($attribute_set_name == 'Hotel Booking')
            {
                $array[] = $data->getData();
            }
        } 
        return $array;
    }
}
