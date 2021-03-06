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
use Ced\Booking\Model\BookingCalendarsFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;

class SearchBooking extends Template
{  
	
   protected $_category;
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        BookingCalendarsFactory $BookingCalendars,
        Timezone $timezone,
    	  array $data=[]
    )
    {
        parent::__construct($context,$data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_objectManager = $objectManager;
        $this->bookingCalendars = $BookingCalendars;
        $this->_timeZone = $timezone;
        $this->_storeManager = $context->getScopeConfig();
    }
   
    public function getcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
       $currDate = date('Y-m-d',$intCurrentTime);
       return $currDate;
    }
    public function getPostData()
    {
      $data = $this->getParamsdata();
      return $data;
    }

    public function getBookingList()
    {
      /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
      $productCollection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
      
      /** Apply filters here */
      $collection = $productCollection->create()
      ->addAttributeToSelect('*')
      ->load();
      $store_id = $this->_storeManager->getStore()->getStoreId();

      foreach($collection as $key => $booking) {  
            
            $reviews[] = $this->_objectManager->create('Magento\Review\Model\Review\Summary')->getCollection()
                                            ->addFieldToFilter('store_id',$store_id)
                                            ->addFieldToFilter('entity_pk_value',$booking->getEntityId())->getData();
             
        $bookingArray = $booking->getData();
        $bookingArray['product_url'] = $booking->getProductUrl();
            foreach ($reviews as $key1 => $value) {
                    foreach ($value as $key2=>$val) {
                        if ($val['entity_pk_value'] == $booking->getEntityId()) {
                            $bookingArray['rating'] = $val['rating_summary'];
                            $bookingArray['reviews'] = $val['reviews_count'];
                        } else {
                            $bookingArray['rating'] = 0;
                            $bookingArray['reviews'] = 0;
                        }
                    }
            }

        $finalarray[] = $bookingArray;
        
      }
      return $finalarray;
    }

    public function getProductData()
    {
        $collection = $this->_objectManager->get('Ced\Booking\Model\CatalogProduct')->getCollection();
        $select= $collection->getSelect();
    
        $select->join(
            ['calendar'=>'ced_booking_calendars'],
            'calendar.calander_booking_sku = main_table.sku',
            array('*')
        );
        $bookingproducts = $this->getBookingList();

        foreach($collection->getData() as $key=>$value) {
          foreach($bookingproducts as $key1=>$data) {
            if ($value['calander_booking_sku'] == $data['sku']) {
              $finalarray[] = array_merge($data,$value);
            }
          }
        }

       return $finalarray;
    }
    
}
