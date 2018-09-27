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
use Magento\Framework\Stdlib\DateTime\Timezone;


class CheckCalendarBooking extends \Magento\Framework\App\Action\Action
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
            JsonFactory $resultJson,
            Timezone $timezone
    		)
    {
    	parent::__construct($context);
    	$this->_objectManager = $context->getObjectManager();
    	$this->resultPageFactory = $resultPageFactory;
    	$this->resultForwardFactory= $resultForwardFactory;
        $this->_resultJsonFactory = $resultJson;
        $this->_timeZone = $timezone;
    }
    
    /**
     * @param execute
     */
    public function execute()
    {
        $productId = $this->getRequest()->getPost('product_id');
        $maxQty = $this->getRequest()->getPost('max_qty');
        $attribute_set_id = $this->getRequest()->getPost('attribute_set_id');

        $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($attribute_set_id)->getAttributeSetName();

        $collection = false;
        if($attribute_set_name == 'Hotel Booking')
        {
            $roomId = $this->getRequest()->getPost('room_id');

            $roomExcludeDaysCollection = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->getCollection()->addFieldToFilter('room_id',$roomId);
            

            $excludeDates=[];
            if (count($roomExcludeDaysCollection)!=0) {
                foreach ($roomExcludeDaysCollection as $days) {

                    $selectedDateFrom=mktime(1,0,0,substr($days->getDayStart(),5,2),substr($days->getDayStart(),8,2),substr($days->getDayStart(),0,4));
                    $selectedDateTo=mktime(1,0,0,substr($days->getDayEnd(),5,2),substr($days->getDayEnd(),8,2),substr($days->getDayEnd(),0,4));

                    if ($selectedDateTo>=$selectedDateFrom)
                    {
                        array_push($excludeDates,date('Y-m-d',$selectedDateFrom));
                        while ($selectedDateFrom<$selectedDateTo)
                        {
                            $selectedDateFrom+=86400;
                            array_push($excludeDates,date('Y-m-d',$selectedDateFrom));
                        }
                    }
                }
            }
            
            $collection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('product_id', $productId);
        }else if($attribute_set_name == 'Daily Rent Booking'){

            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);

            $nonWorkingDays = json_decode($product->getNonWorkingDays());

            if (count($nonWorkingDays)!=0) {

              foreach ($nonWorkingDays as $nonWorkingdata) {

                  $isDelete = isset($nonWorkingdata->is_delete) ? true : false;

                  if (isset($nonWorkingdata->type) && $nonWorkingdata->type == 'date' && !($isDelete)) {

                    $excludeDates=[];
                    foreach ($nonWorkingdata->values as $dates) {

                      $startdate = explode(' ',$dates->start_date);
                      $enddate = explode(' ',$dates->end_date);

                      $nonWorkingDateFrom=mktime(1,0,0,substr($startdate[0],5,2),substr($startdate[0],8,2),substr($startdate[0],0,4));
                      $nonWorkingDateTo=mktime(1,0,0,substr($enddate[0],5,2),substr($enddate[0],8,2),substr($enddate[0],0,4));

                      if ($nonWorkingDateTo>=$nonWorkingDateFrom)
                      {
                        array_push($excludeDates,date('Y-m-d',$nonWorkingDateFrom));
                        while ($nonWorkingDateFrom<$nonWorkingDateTo)
                          {
                            $nonWorkingDateFrom+=86400;
                            array_push($excludeDates,date('Y-m-d',$nonWorkingDateFrom));
                          }
                      }
                    }
                  }
                }
              }

            $collection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id', $productId);
        }else{
            $collection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id', $productId);
        }

       

        $bookedDate = [];
        foreach ($collection as $order) {

            $bookingStartDate = date('Y-m-d', strtotime($order->getBookingStartDate()));

            while (strtotime($bookingStartDate) <= strtotime($order->getBookingEndDate())) {
                 $date = \DateTime::createFromFormat("Y-m-d", $bookingStartDate);
                 $dateInd =   $date->format("d");
                 $monthInd =   $date->format("m");
                  if(!isset($bookedDate[$monthInd][$dateInd]))
                    $bookedDate[$monthInd][$dateInd] = 0;

            if($attribute_set_name == 'Hotel Booking')
                $bookedDate[$monthInd][$dateInd] += 1;
            else if($attribute_set_name == 'Daily Rent Booking')
                $bookedDate[$monthInd][$dateInd] += $order->getQty();

                $bookingStartDate = date ("Y-m-d", strtotime("+1 days", strtotime($bookingStartDate)));    
            }
        }

        $start_date =  $this->getRequest()->getPost('start');
        $end_date = $this->getRequest()->getPost('end');
        $events = [];
        $currentDate = $this->getcurrentdate();
        //if (strtotime($start_date) >= strtotime($currentDate)) {
            while (strtotime($start_date) <= strtotime($end_date)) {
                $date = \DateTime::createFromFormat("Y-m-d", $start_date);
                $dateInd =   $date->format("d");
                $monthInd =   $date->format("m");

                $decrement = 0;
                if(isset($bookedDate[$monthInd][$dateInd])) 
                    $decrement = (int)$bookedDate[$monthInd][$dateInd];
                
                $allowedQty = $maxQty - $decrement;
                if (preg_match('/-/', $allowedQty)) {

                    $allowedQty = 0;

                }
                if (strtotime($start_date) >= strtotime($currentDate)) {
                    if (isset($excludeDates) && count($excludeDates)!=0) {

                            if (in_array($start_date, $excludeDates)) {

                                $allowedQty = 0;
                                $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date];

                            } else {
                                $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date];
                            }

                    } else {
                        $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date];
                    }
                    
                }
              
                $start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date))); 
            }
       
        $resultJson =  $this->_resultJsonFactory->create();

        return $resultJson->setData($events);

    }

    public function getcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
       $currDate = date('Y-m-d',$intCurrentTime);
       return $currDate;
    }
}
