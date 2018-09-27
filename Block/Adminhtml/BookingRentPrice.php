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
namespace Ced\Booking\Block\Adminhtml;
 
use Magento\Framework\View\Element\FormKey;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Directory\Model\Currency;
use Ced\Booking\Model\BookingCalendarsFactory;
class BookingRentPrice extends \Magento\Backend\Block\Template
{
	
	 protected $_roomsFactory;
	 
	 protected $_roomtypesFactory;
	
	 protected $_bookingimages;
	
	 protected $_formKey;
	function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		FormKey $_formKey,
		\Magento\Framework\objectManagerInterface $_objectManager,
		Timezone $timezone,
		Currency $currency,
		BookingCalendarsFactory $calendarsFactory,
		array $data = []
	)
	{

		$this->_objectManager = $_objectManager;
		$this->_formkey = $_formKey;
		$this->_timeZone = $timezone;
		$this->_currency = $currency;
		$this->_calendarsFactory = $calendarsFactory;
		parent::__construct($context, $data);
	}
	
	public function getBookingItems()
	{

        $calId = $this->getRequest()->getParam('calendar_id');
        $model = $this->_calendarsFactory->create();
        $bookingcalendaritems = $model->load($calId); 
        return  $bookingcalendaritems;
	}

    public function getbookingcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
	   $currDate = date('Y-m-d',$intCurrentTime);
	   return $currDate;
    }
    public function getRoomPriceItems()
    {
    	
  		
    	$items = $this->_objectManager->get('Ced\Booking\Model\BookingCalendars')->getCollection();
      	
    	if($this->getBookingId()){
    		$items->addFieldToFilter('calendar_booking_id',$this->getBookingId());
    	}else{
    		//In case of rent sku need (its alternate of room_id)
    		$items->addFieldToFilter('calander_booking_sku',$this->getSku());
    	}
    	return $items;
    }
    public function getcurrencysymbol()
    {
    	return $this->_currency->getCurrencySymbol();
    }

    //rajneesh

    public function getBookingIdBySku()
    {
    	$items = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\BookingCalendars\Collection')->addFieldToFilter('calendar_booking_id',$bookingId);
    	return $items;
    }


}