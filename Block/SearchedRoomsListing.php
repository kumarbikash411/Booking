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
use Ced\Booking\Model\ProductFacilityRelationFactory;
use Ced\Booking\Model\RoomsFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data;
use Ced\Booking\Model\BookingCalendarsFactory;

/**
 * 
 * @author cedcoss
 *
 */
class SearchedRoomsListing extends Template
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
        ProductFacilityRelationFactory $ProductFacilityRelation,
        RoomsFactory $RoomsFactory,
        Timezone $timezone,
        Data $price,
        BookingCalendarsFactory $bookingCalendars,
        \Magento\Framework\Registry $registry,
    	  array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_productFacilityRelation = $ProductFacilityRelation;
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_RoomsFactory = $RoomsFactory;
        $this->_timeZone = $timezone;
        $this->price = $price;  
        $this->scopeConfig = $context->getScopeConfig();
        $this->bookingCalendars = $bookingCalendars;  
        $this->setCollection($this->getRoomsData());  
        $this->registry = $registry; 
    } 
    
    public function getCurrentSymbol()
    {
        return $this->price;
    }

    // protected function _prepareLayout()
    // {
    //     parent::_prepareLayout();
        
    //     if ($this->getCollection()) {
    //         // create pager block for collection 
    //         $pager = $this->getLayout()->createBlock(
    //             'Magento\Theme\Block\Html\Pager',
    //             'booking1.grid.record.pager'
    //         )->setAvailableLimit(array(1=>1,2=>2))->setShowPerPage(1)->setCollection(
    //             $this->getCollection() // assign collection to pager
    //         );
    //         $this->setChild('pager', $pager);// set pager block in layout
    //         $this->registry->register('rooms_collection', $this->getCollection());
    //     }

    //     return $this;
    // }
  
    // /**
    //  * @return string
    //  */
    // // method for get pager html
    // public function getPagerHtml()
    // {
    //     return $this->getChildHtml('pager');
    // }

    // public function getRoomCollection()
    // {
    //     return $this->registry->registry('rooms_collection');
    // } 

 
    public function getProductId()
    {
      if ($this->getRequest()->getParam('product_id')) {
         $id = $this->getRequest()->getParam('product_id');
      } elseif ($this->getRequest()->getParam('p')) {
        $id = '';
      } else {
         $id = $this->_coreRegistry->registry('current_product')->getId();
      }
      return $id;
    }

    public function getParams()
    {
        $param = $this->getRequest()->getParams();
        return $param;
    }

    public function getRoomExcludeDays()
    {
        $paramData = $this->getParams();
        $excludeDaysRoomIds = [];
        $roomIds = [];
        if (isset($paramData['check_in']) && $paramData['check_in']!='' && isset($paramData['check_out']) && $paramData['check_out']!='') {

            $selectedDates=[];
            $selectedDateFrom=mktime(1,0,0,substr($paramData['check_in'],5,2),substr($paramData['check_in'],8,2),substr($paramData['check_in'],0,4));
            $selectedDateTo=mktime(1,0,0,substr($paramData['check_out'],5,2),substr($paramData['check_out'],8,2),substr($paramData['check_out'],0,4));

            if ($selectedDateTo>=$selectedDateFrom)
            {
                array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                while ($selectedDateFrom<$selectedDateTo)
                {
                    $selectedDateFrom+=86400;
                    array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                }
            }
            $finalArray = [];
            foreach ($selectedDates as $dates) {
                $excludeDaysArray = [];
                $roomExcludeDays = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->getCollection()->addFieldToFilter('day_start',['lteq'=>$dates])->addFieldToFilter('day_end',['gteq'=>$dates]);
               
                if (count($roomExcludeDays) !=0) {
                    foreach ($roomExcludeDays as $room) {
                        $excludeDaysArray['room_id'] = $room->getRoomId();
                    }
                }
                $finalArray[$dates] = $excludeDaysArray;
            }
            foreach ($finalArray as $array) {
                if (count($array)!=0) {
                    $roomIds[] = $array['room_id'];
                }
            }
            if (count($roomIds)!=0) {
                $excludeDaysRoomIds = array_unique($roomIds); 
            }
        }
        return $excludeDaysRoomIds;
    }
  
    /* join all room table data */
    public function getRoomsData()
    {
        $param = $this->getParams();
        $excludeDays = $this->getRoomExcludeDays();

        $collection = $this->_objectManager->get('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('main_table.product_id',$this->getProductId());
        $collection->getSelect()->joinLeft(
                ['image'=>'ced_booking_room_image_relation'],
                'main_table.id = image.room_id',
                array('image.image_name')
        )->group('main_table.id');
        $collection->addFieldToFilter('status',1);
        if (isset($param['category']) && $param['category']!='') {

            $collection->addFieldToFilter('main_table.room_category_id',$param['category']);

        } elseif (isset($param['check_in']) && $param['check_in']!='' && isset($param['check_out']) && $param['check_out']!='' && count($excludeDays)!=0) {

            $collection->addFieldToFilter('main_table.id',['neq'=>$excludeDays]);

        } elseif (isset($param['category']) && $param['category']!='' && isset($param['check_in']) && $param['check_in']!='' && isset($param['check_out']) && $param['check_out']!='' && count($excludeDays)!=0) {

            $collection->addFieldToFilter('main_table.id',['neq'=>$excludeDays])->addFieldToFilter('main_table.room_category_id',$param['category']);
        } 
        return $collection; 
    }

    /* get hotel Facilities Relation */
    public function getFacilitiesRelation()
    {
        $facilitiesrelation = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('product_id',$this->getProductId());
        return $facilitiesrelation;
    }

    
}
