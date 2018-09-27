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
use Ced\Booking\Model\ProductFacilityRelationFactory;


class BookingRentContent extends \Ced\Booking\Block\AbstractBooking
{  
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductFacilityRelationFactory $ProductFacilityRelation,
        \Magento\Framework\Registry $coreRegistry,
        Timezone $timezone,
    	array $data=[]
    )
    {
        parent::__construct($context, $coreRegistry, $data);
        $this->_productFacilityRelation = $ProductFacilityRelation;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_timeZone = $timezone;
        $this->scopeConfig = $context->getScopeConfig();  
    }


    public function getProductId()
    {
        $id = $this->_coreRegistry->registry('current_product')->getId();
        return $id;
    }
	

    public function getcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
       $currDate = date('Y-m-d',$intCurrentTime);
       return $currDate;
    }

    /**
     * @return mixed
     *
     */
    public function getBookingTypeRentFacilities()
    {
        $model =  $this->_productFacilityRelation->create();
        $bookingfacilities = $model->getCollection()->addFieldToFilter('product_id',$this->getProductId());
        $facilityids = $bookingfacilities->getColumnValues('facility_id');

        if (count($facilityids)!=0) {

            $facilitiesCollection = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
            $facilitiesCollection->addFieldToFilter('id', $facilityids);
            return $facilitiesCollection;
        } else {
            return '';
        }

    }

    /**
     * @return mixed
     *
     */
    public function getCurrentMonthAvailability()
    {
        $product = $this->getProduct();
        $maxQty = $product->getStockQtyForADay();
        $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($product->getAttributeSetId())->getAttributeSetName();
        $collection = false;
        if($attribute_set_name == 'Hotel Booking')
        {
            $collection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('product_id', $product->getId());
        }else{
            $collection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id', $product->getId());
        }


       

        $bookedDate = [];
        foreach ($collection as $order) {

            $bookingStartDate = date('Y-m-d', strtotime($order->getBookingStartDate()));

            while (strtotime($bookingStartDate) <= strtotime($order->getBookingEndDate())) {
                 $date = \DateTime::createFromFormat("Y-m-d", $bookingStartDate);
                 $dateInd =   $date->format("d");
                  if(!isset($bookedDate[$dateInd]))
                    $bookedDate[$dateInd] = 0;

                $bookedDate[$dateInd] += $order->getQty();
                $bookingStartDate = date ("Y-m-d", strtotime("+1 days", strtotime($bookingStartDate)));    
            }
        }

        $start_date =  date('Y-m-01');        
        $end_date = date('Y-m-t');
        $events = [];
        while (strtotime($start_date) <= strtotime($end_date)) {
            $date = \DateTime::createFromFormat("Y-m-d", $start_date);
            $dateInd =   $date->format("d");

            $decrement = 0;
            if(isset($bookedDate[$dateInd])) 
                $decrement = (int)$bookedDate[$dateInd];

            $allowedQty = $maxQty - $decrement;

            

            $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date];
            $start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }



        return json_encode($events);

    }    


}
