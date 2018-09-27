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
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Controller\Booking;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;


class CheckHourlyCalendarBooking extends \Magento\Framework\App\Action\Action
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
        $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $maxQty = $product->getStockQtyForAInterval();
        $collection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id', $productId);


        $bookedDate = [];
        foreach ($collection as $order) {

            $bookingStartDate = date('Y-m-d', strtotime($order->getBookingStartDate()));
            $bookedStartTime = date('H:i:s', strtotime($order->getBookingStartDate()));
            $bookedEndTime = date('H:i:s', strtotime($order->getBookingEndDate()));

            while (strtotime($bookingStartDate) <= strtotime($order->getBookingEndDate())) {
                $date = \DateTime::createFromFormat("Y-m-d", $bookingStartDate);
                $dateInd =   $date->format("d");
                $monthInd =   $date->format("m");

                while(strtotime($bookedStartTime) < strtotime($bookedEndTime)){
                    if(!isset($bookedDate[$monthInd][$dateInd][$bookedStartTime]))
                        $bookedDate[$monthInd][$dateInd][$bookedStartTime] = 0;
                    $bookedDate[$monthInd][$dateInd][$bookedStartTime] += $order->getQty();
                    $bookedStartTime = date('H:i:s',strtotime("+1 hour", strtotime($bookedStartTime)));
                }
                $bookingStartDate = date ("Y-m-d", strtotime("+1 days", strtotime($bookingStartDate)));    
            }
        }

       

        $startTime = $product->getServiceStartTime();
        $endTime = $product->getServiceEndTime();

        $start_date =  $this->getRequest()->getPost('start');
        $end_date = $this->getRequest()->getPost('end');
        $events = [];
        $currentDate = $this->getcurrentdate();

        $nonWorkingDays = json_decode($product->getNonWorkingDays());
        $nonWorkingDates=[];
        $nonWorkingDaysInterval = [];
        $nonWorkingFullDays = [];

        if (count($nonWorkingDays)!=0) {

          foreach ($nonWorkingDays as $nonWorkingdata) {

              $isDelete = isset($nonWorkingdata->is_delete) ? true : false;

              if (isset($nonWorkingdata->type) && $nonWorkingdata->type == 'date' && !($isDelete)) {

                
                foreach ($nonWorkingdata->values as $dates) {

                  $startdate = explode(' ',$dates->start_date);
                  $enddate = explode(' ',$dates->end_date);

                  $nonWorkingDateFrom=mktime(1,0,0,substr($startdate[0],5,2),substr($startdate[0],8,2),substr($startdate[0],0,4));
                  $nonWorkingDateTo=mktime(1,0,0,substr($enddate[0],5,2),substr($enddate[0],8,2),substr($enddate[0],0,4));

                  if ($nonWorkingDateTo>=$nonWorkingDateFrom)
                  {
                    array_push($nonWorkingDates,date('Y-m-d',$nonWorkingDateFrom));
                    while ($nonWorkingDateFrom<$nonWorkingDateTo)
                      {
                        $nonWorkingDateFrom+=86400;
                        array_push($nonWorkingDates,date('Y-m-d',$nonWorkingDateFrom));
                      }
                  }
                }
              } elseif(isset($nonWorkingdata->type) && $nonWorkingdata->type == 'days') {

                if($nonWorkingdata->non_working_day_type == 'full_day') {

                    $nonWorkingFullDays[] = $nonWorkingdata->days;

                } elseif($nonWorkingdata->non_working_day_type == 'interval') {

                    foreach ($nonWorkingdata->interval_options_time->interval_options_time as $options) {

                        $nonWorkingDaysInterval[] = ['day'=> $nonWorkingdata->days,
                                                      'start_time' => $options->appointment_start_time,
                                                      'end_time' => $options->appointment_end_time
                                                    ];

                    }
                }

              } 
            }
          }

        while ($start_date <= $end_date) {

            if ($start_date >= $currentDate){

                $date = \DateTime::createFromFormat("Y-m-d", $start_date);
                $dateInd =   $date->format("d");
                $monthInd =   $date->format("m");
                $startTime = $product->getServiceStartTime();
                if ($startTime >= $endTime) {

                    while($startTime <= $endTime) {

                        $nonWorkingTime = false;

                        $start_time = date("H:i:s", strtotime($startTime));

                        $timestamp = strtotime($start_date);
                        $selectedDay = date("l", $timestamp);

                        if (count($nonWorkingDaysInterval)!=0) { 

                            foreach ($nonWorkingDaysInterval as $nonWorkinginterval) {
                                if (lcfirst($selectedDay) == $nonWorkinginterval['day']) {

                                    if (strtotime($start_time) >= strtotime(date('H:i:s',strtotime($nonWorkinginterval['start_time']))) && strtotime($start_time) <= strtotime(date('H:i:s',strtotime($nonWorkinginterval['end_time'])))) {

                                            $nonWorkingTime = true;
                                        }
                                }
                            }
                        }


                        
                        $decrement = 0;
                        if(isset($bookedDate[$monthInd][$dateInd][$startTime])) 
                            $decrement = (int)$bookedDate[$monthInd][$dateInd][$startTime];

                        $allowedQty = $maxQty - $decrement;

                            if ((count($nonWorkingDates)!=0 && in_array($start_date, $nonWorkingDates)) || ( count($nonWorkingFullDays)!=0 && in_array(lcfirst($selectedDay), $nonWorkingFullDays))) {
                        
                                $events[] = ['title' => '0 Available', 'start' => $start_date];

                            } elseif($nonWorkingTime) {
                                 $events[] = ['title' => '0 Available', 'start' => $start_date.'T'.$start_time];
                            } else {
                                $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date.'T'.$start_time];
                            }
                            $startTime = date("H:i:s",strtotime("+1 hour", strtotime($start_time)));
                        
                    }
                } else {
                    $count = 0;
                    while($startTime <= $endTime) {
                        $count++;
                        $start_time = date("H:i:s", strtotime($startTime));
                        $nonWorkingTime = false;

                        $start_time = date("H:i:s", strtotime($startTime));
                        $decrement = 0;
                        if(isset($bookedDate[$monthInd][$dateInd][$startTime])) 
                            $decrement = (int)$bookedDate[$monthInd][$dateInd][$startTime];

                            $allowedQty = $maxQty - $decrement;

                            $timestamp = strtotime($start_date);
                            $selectedDay = date("l", $timestamp);

                            if (count($nonWorkingDaysInterval)!=0) { 

                                foreach ($nonWorkingDaysInterval as $nonWorkinginterval) {
                                    if (lcfirst($selectedDay) == $nonWorkinginterval['day']) {


                                        if (strtotime($start_time) >= strtotime(date('H:i:s',strtotime($nonWorkinginterval['start_time']))) && strtotime($start_time) <= strtotime(date('H:i:s',strtotime($nonWorkinginterval['end_time'])))) {

                                            $nonWorkingTime = true;
                                        }
                                    }
                                }
                            }
                            if (count($nonWorkingDates)!=0 && in_array($start_date, $nonWorkingDates) || ( count($nonWorkingFullDays)!=0 && in_array(lcfirst($selectedDay), $nonWorkingFullDays))) {
                        
                                $events[] = ['title' => '0 Available', 'start' => $start_date];

                            } elseif($nonWorkingTime) {
                                 $events[] = ['title' => '0 Available', 'start' => $start_date.'T'.$start_time];
                            }else {
                        
                                $events[] = ['title' => $allowedQty.' Available', 'start' => $start_date.'T'.$start_time];
                            }
                            $startTime = date("H:i a",strtotime("+1 hour", strtotime($start_time)));
                    }

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
