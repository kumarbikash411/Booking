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

use Ced\Booking\Model\CatalogProductFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\Helper\Data;

class BookingPopup extends Template
{    
   /**
    * 
    * @param \Magento\Framework\View\Element\Template\Context $context
    * @param \Magento\Framework\Registry $coreRegistry
    * @param ProductFacilityRelationFactory $ProductFacilityRelation
    * @param RoomsFactory $RoomsFactory
    * @param array $data
    */
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        CatalogProductFactory $CatalogProductFactory,
        Timezone $timezone,
        Data $price,
    	array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_CatalogProduct = $CatalogProductFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_timeZone = $timezone;
        $this->_price = $price;
    }
    public function getProductId()
    {
        $product_id = $this->getRequest()->getParam('product_id');
        return $product_id;
    }
    public function getProduct()
    {
        $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($this->getProductId());
        return $product;
    }
    public function getRoomId()
    {
    	$room_id = $this->getRequest()->getParam('room_id');
    	return $room_id;
    }

    public function getcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
       $currDate = date('Y-m-d',$intCurrentTime);
       return $currDate;
    }

    public function getCurrenySymbol()
    {
        return $this->_price;
    }
    
    public function getroomsimage()
    {
        $roomid = $this->getRoomId();
        $image = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\RoomsImageRelation\Collection')
                                             ->addFieldToFilter('room_id',$roomid);  
        return $image; 
    }
    public function getRoomsData()
    {
        $collection = $this->_objectManager->get('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('main_table.id',$this->getRoomId());
        $collection->getSelect()->joinLeft(
                ['amenities'=>'ced_booking_room_amenities'],
                'main_table.id = amenities.room_id',
                array('main_table.id' , 'amenities.amenity_id') 
          )->joinLeft(
                ['image'=>'ced_booking_room_image_relation'],
                'main_table.id = image.room_id',
                array('image.image_name')
         )->joinLeft(
                ['roomNumber'=>'ced_booking_room_numbers'],
                ' main_table.id = roomNumber.room_id',
                array('roomNumber.room_numbers')
         );
     
       return $collection->getData();
    }

    /* get Room Facilities Relation */
    public function getRoomsFacilitiyRelation()
    {
        $roomAmenities = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->getCollection()->addFieldToFilter('room_id',$this->getRoomId());
        return $roomAmenities;
    }

}
