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

namespace Ced\Booking\Controller\Booking;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class Calendar extends \Magento\Framework\App\Action\Action
{
    /**
     * @param resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param resultRedirectFactory
     */
    
    protected $resultRedirectFactory;
    
    /**
     * @param resultForwardFactory
     */
    
    protected $resultForwardFactory;
    
    /**
     * @param resultRedirect
     */
    
    protected $resultRedirect;
    
    /**
     * @param Magento\Framework\App\Action\Context $context
     * @param Magento\Framework\View\Result\PageFactory
     * @param Magento\Backend\Model\View\Result\Redirect
     * @param Magento\Framework\Controller\Result\ForwardFactory
     */

    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\Redirect $resultRedirectFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        JsonFactory $resultJsonFactory,
        PriceHelper $priceHelper
        )
    {
        parent::__construct($context);
        $this->_objectManager = $context->getObjectManager();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory= $resultForwardFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_priceHelper = $priceHelper;
    }
    
    /**
     * @param execute
     */
    public function execute()
    {
       // $bookingId = $this->_request->getParam('booking_type');
        $product_id = $this->_request->getParam('booking_id');
        $booking_type = $this->_request->getParam('booking_type');

        $productdata = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\CatalogProduct\Collection')->addFieldToFilter('booking_product_id',$product_id)->getData();

        if ($booking_type == 'hotel') {

            $Calendars  = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\BookingCalendars\Collection')->addFieldToFilter('calendar_booking_id',$product_id);

        } else {

            $Calendars  = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\BookingCalendars\Collection')->addFieldToFilter('calander_booking_sku',$productdata[0]['sku'])->addFieldToFilter('calendar_booking_type',$booking_type);

        }

        if(count($Calendars))
        {
            foreach($Calendars as $key => $calendar)
            {
                $Calendarsarray[$key]['start_date'] = $calendar->getCalendarStartdate();
                $Calendarsarray[$key]['end_date'] = $calendar->getCalendarEnddate();
                $Calendarsarray[$key]['status'] = $calendar->getCalendarStatus();
                $Calendarsarray[$key]['price'] = $calendar->getCalendarPrice();
                $Calendarsarray[$key]['promo'] = $calendar->getCalendarPromo();
                $Calendarsarray[$key]['qty'] = $calendar->getCalendarQty();
                $Calendarsarray[$key]['default_value'] = $calendar->getCalendarDefaultValue();
            }
            $DefaultCalendar = [];
            foreach($Calendarsarray as $key => $arCalendar)
            {
                if($arCalendar['default_value'] == 1)
                {
                    $DefaultCalendar = $arCalendar;
                    unset($Calendarsarray[$key]);
                    break;
                }
            }
            if(count($DefaultCalendar))
            {
                $Calendarsarray[] = $DefaultCalendar; 
            }
            //reset array
            $dataCalendar = array_values($Calendarsarray);

            //change format price
            foreach($dataCalendar as $pKey => $value)
            {
                if($value['price'] > 0)
                {
                    $dataCalendar[$pKey]['text_price'] = $this->_priceHelper->currency($value['price'],false,false);
                }
                if($value['promo'] > 0)
                {
                    $dataCalendar[$pKey]['text_promo'] = $this->_priceHelper->currency($value['promo'],false,false);
                }
            }
        } else {
            $dataCalendar = Null;
        }

        $arrayData = ['data_calendar'=>$dataCalendar];
        $resultJson = $this->_resultJsonFactory->create();
        return $resultJson->setData($arrayData);
    }
}
