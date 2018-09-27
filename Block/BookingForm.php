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

class BookingForm extends Template
{    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Booking\Helper\Data $helperdata,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->_objectManager = $objectManager;
        $this->_timeZone = $timezone;
        $this->_helper = $helperdata;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
      
    }

    /* get hotel booking product*/ 
    public function getBookingType()
    {

        $productId = $this->_coreRegistry->registry('current_product')->getId(); 
        $productCollection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        
        /** Apply filters here */
        $collection = $productCollection->create()
                                        ->addFieldToFilter('entity_id',$productId);
        foreach ($collection as $coll) {
			$attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($coll->getAttributeSetId())->getAttributeSetName();
        }
     
        return $attribute_set_name;
    }
}
