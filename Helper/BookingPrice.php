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
namespace Ced\Booking\Helper;
 
use Ced\Booking\Helper\Data;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class BookingPrice extends Data
{
  
  public function __construct(
    PriceHelper $priceHelper,
    \Magento\Framework\ObjectManagerInterface $objectManager
  ) 
  {
    $this->_priceHelper = $priceHelper;
    $this->_objectManager = $objectManager;       
  }

  public function getPrice($postdata)
  {
        $totaldays = '';
        $totalprice = '';
        $msg = '';
        $BookedDates = [];
        $bookedDatesData = [];
        $bookedRoomNumbers = [];
        $availableRooms =[];
        $excludedaysItems = [];

        if ($postdata['data']['check_in']=='' || $postdata['data']['check_out']=='') {

                $msg = __('Please select date.');

        } else {

              $roomData = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($postdata['data']['room_id']);
              if ($roomData->getMinBookingAllowedDays()!='' && $roomData->getMaxBookingAllowedDays()!='') {

                $date1 = strtotime($postdata['data']['check_in']);
                $date2 = strtotime($postdata['data']['check_out']);
                $diff = $date2 - $date1;
                $totalbookingdays = floor($diff/(60*60*24));

                $roomBookingAllowedDays = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('id',$postdata['data']['room_id'])->addFieldToFilter('min_booking_allowed_days',['lteq'=>$totalbookingdays])->addFieldToFilter('max_booking_allowed_days',['gteq'=>$totalbookingdays]);
              }


               if (isset($roomBookingAllowedDays) && count($roomBookingAllowedDays)==0) {
                  $msg = 'You can not book less than '.$roomData->getMinBookingAllowedDays().' day and more than '.$roomData->getMaxBookingAllowedDays().' days';
                } else {

                  $selectedDates=[];
                  $selectedDateFrom=mktime(1,0,0,substr($postdata['data']['check_in'],5,2),substr($postdata['data']['check_in'],8,2),substr($postdata['data']['check_in'],0,4));
                  $selectedDateTo=mktime(1,0,0,substr($postdata['data']['check_out'],5,2),substr($postdata['data']['check_out'],8,2),substr($postdata['data']['check_out'],0,4));

                  if ($selectedDateTo>=$selectedDateFrom)
                  {
                    array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                    while ($selectedDateFrom<$selectedDateTo)
                    {
                      $selectedDateFrom+=86400;
                      array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                    }
                  }

                  foreach ($selectedDates as $selecteddate) {
                          
                    $roomExcludeDaysCollection = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->getCollection()->addFieldToFilter('room_id',$postdata['data']['room_id'])->addFieldToFilter('day_start',['lteq'=>$selecteddate])->addFieldToFilter('day_end',['gteq'=>$selecteddate]);

                    $excludedaysItems[$selecteddate] = $roomExcludeDaysCollection->getData();

                    $bookedItems = [];
                    $status = [0=>'pending',1=>'complete'];
                    $RoomOrderModel = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('room_id',$postdata['data']['room_id'])->addFieldToFilter('status',['in'=>$status])->addFieldToFilter('booking_start_date',['lteq'=>$selecteddate])->addFieldToFilter('booking_end_date',['gteq'=>$selecteddate]);

                    foreach ($RoomOrderModel as $order) {
                      $bookedItems[] = $order->getRoomNumberId();
                      $bookedRoomNumbers[] = $order->getRoomNumberId();
                    }
                      $bookedDatesData[$selecteddate] = $bookedItems;
                  } 
                  $unavailableDays = [];
                  foreach ($excludedaysItems as $key=>$items) {
                    if (count($items)!=0) {
                      $unavailableDays[] = $key;
                    }
                  }
                  $roomNumbersIdsAll = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->getCollection()->addFieldToFilter('room_id',$postdata['data']['room_id'])->getAllIds();
                  $allowedRooms = [];
                  $bookedRoomNumbers = array_unique($bookedRoomNumbers);
                  $availableRooms = array_diff($roomNumbersIdsAll, $bookedRoomNumbers);
                  if (count($unavailableDays) != 0 ) {
                    $msg = count($unavailableDays) == 1 ? 'Booking on '.$unavailableDays[0].' is not available.' :  'Booking from '.$unavailableDays[0].' to '.end($unavailableDays).' is not available.';
                  } elseif (count($availableRooms) == 0) {
                    $msg = 'No rooms are available.';
                  } elseif (count($availableRooms) != 0) {
                      if ($postdata['data']['qty'] > count($availableRooms) ) {
                        $msg =  count($availableRooms) > 1 ? count($availableRooms).' rooms are available.' : count($availableRooms).' room is available.';
                      } elseif ($postdata['data']['qty'] <= count($availableRooms) ) {
                        $date1 = strtotime($postdata['data']['check_in']);
                        $date2 = strtotime($postdata['data']['check_out']);
                        $diff = $date2 - $date1;
                        $totaldays = floor($diff/(60*60*24));
                        $totalprice = $totaldays * $postdata['data']['price'];
                      }
                  }
                }
          }
        $data = ['msg'=>$msg,'totaldays'=>$totaldays,'totalprice'=>$totalprice,'availablerooms'=>$availableRooms] ;
        return $data;
  }

  public function getRentPrice($postdata,$product)
  {
        $totaldays = '';
        $totalprice = '';
        $totalhours = '';
        $msg = '';
        $bookDateArray = [];
        $excludeDate = false;
        $nonWorkingFullDays = [];
        $nonWorkingDaysInterval = [];
        $nonWorkingDays = json_decode($product->getNonWorkingDays());
  
        if (count($nonWorkingDays)!=0) {

          foreach ($nonWorkingDays as $nonWorkingdata) {

              $isDelete = isset($nonWorkingdata->is_delete) ? true : false;

              if (isset($nonWorkingdata->type) && $nonWorkingdata->type == 'date' && !($isDelete)) {

                $nonWorkingDates=[];
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

        $orderedProductCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id',$postdata['data']['product_id']);
        $selectedDates=[];
        $status = [0=>'pending',1=>'complete'];

        $selectedDateFrom=mktime(1,0,0,substr($postdata['data']['check_in'],5,2),substr($postdata['data']['check_in'],8,2),substr($postdata['data']['check_in'],0,4));
        $selectedDateTo=mktime(1,0,0,substr($postdata['data']['check_out'],5,2),substr($postdata['data']['check_out'],8,2),substr($postdata['data']['check_out'],0,4));

        if ($selectedDateTo>=$selectedDateFrom)
        {
          array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
          while ($selectedDateFrom<$selectedDateTo)
          {
            $selectedDateFrom+=86400;
            array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
          }
        }

        if ($postdata['data']['rent']=='daily') {

             if ($postdata['data']['check_in']=='' || $postdata['data']['check_out']=='') {

                $msg = __('Please select date.');

            } else {
             
                $bookedDates = [];
                
                
                if (count($orderedProductCollection)!=0 || isset($nonWorkingDates)) {
                  foreach ($selectedDates as $date) {

                    if (isset($nonWorkingDates) && count($nonWorkingDates)!=0 && in_array($date, $nonWorkingDates)) {

                      $excludeDate = true;

                      $msg = __('Booking not available for selected dates,Please check availability in calendar.');

                    } else {

                        $orderedProductModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id',$postdata['data']['product_id'])->addFieldToFilter('status',['in'=>$status])->addFieldToFilter('booking_start_date',['lteq'=>$date])->addFieldToFilter('booking_end_date',['gteq'=>$date])->getColumnValues('qty');

                        $bookedDaysQty = 0;

                        if (count($orderedProductModel)==1) {
                          $bookedDaysQty = $orderedProductModel[0];
                        } elseif (count($orderedProductModel) > 1) {
                            foreach ($orderedProductModel as $orderedQty) {
                              $bookedDaysQty = $orderedQty + $bookedDaysQty;
                            }
                        }
                        $bookedDates[$date] = $bookedDaysQty;
                      }
                  } 
                }
                $stockQty = $product->getStockQtyForADay();
                $bookedQty = [];
                if (count($bookedDates)!=0) {
                  foreach ($bookedDates as $key=>$bookqty) {

                    if ($bookqty!=0) {

                      if ($bookqty >= $stockQty) {
                          $bookDateArray[] = $key;
                      } else {
                        $bookedQty[] = $bookqty;
                      }

                      
                    }
                  }
                }

               
                if (isset($bookedQty)) {
                  $bookedQtyArray = array_unique($bookedQty);
                  foreach ($bookedQtyArray as $value) {
                    $leftQty = $stockQty - $value;
                  }
                }

                if (count($selectedDates) < $product->getBookingMinDays() || count($selectedDates) > $product->getBookingMaxDays()) {

                      $MindayText = $product->getBookingMinDays() > 1 ? 'days' : 'day';
                      $MaxdayText = $product->getBookingMaxDays() > 1 ? 'days' : 'day';
                      $msg = 'You can not book less than '.$product->getBookingMinDays().' '.$MindayText.' and greather than '.$product->getBookingMaxDays().' '.$MaxdayText.'.';
                } elseif (count($bookDateArray)==0 && !$excludeDate) {
                  if (isset($leftQty) && $postdata['data']['qty'] > $leftQty) {
                    $msg = __('Available Quantity is ').$leftQty;
                  } else {
                    $date1 = strtotime($postdata['data']['check_in']);
                    $date2 = strtotime($postdata['data']['check_out']);
                    $diff = $date2 - $date1;
                    $totaldays = floor($diff/(60*60*24)) +1;
                    $totalprice = $totaldays * $postdata['data']['price'];
                  }
                } elseif (!$excludeDate) {

                  $msg = count($bookDateArray)==1 ? __('Available quantity for '.$bookDateArray[0].' is 0.') : __('Available quantity from '.$bookDateArray[0].' to '.end($bookDateArray).' is 0.');
                }
          }


        } elseif ($postdata['data']['rent']=='hourly') {

          $nonWorkingDateFlag = false;
          if  (isset($nonWorkingDates) && count($nonWorkingDates)!=0) {

                foreach ($selectedDates as $sDate) {
                  if (in_array($sDate,$nonWorkingDates)) {

                    $nonWorkingDateFlag = true;
                  }
                }

          }
          $nonWorkingFullDayFlag = false;
          if (count($nonWorkingFullDays)!=0) {

                 foreach ($selectedDates as $sDate) {

                    $timestamp = strtotime($sDate);
                    $selectedDay = date("l", $timestamp);

                    if (in_array(lcfirst($selectedDay), $nonWorkingFullDays)) {

                      $nonWorkingFullDayFlag = true;
                    }
                 }
               } 

            if (count($nonWorkingDaysInterval)!=0) {

                  $nonWorkingInterval = false;

                    foreach ($selectedDates as $sDate) { 

                        $timestamp = strtotime($sDate);
                        $selectedDay = date("l", $timestamp);

                        foreach ($nonWorkingDaysInterval as $interval) {

                          $postFromTime = explode(' ',$postdata['data']['check_in']);
                          $postToTime = explode(' ',$postdata['data']['check_out']); 

                          $startTime = $postFromTime[1].' '.$postFromTime[2];
                          $endTime = $postToTime[1].' '.$postToTime[2];

                          while(strtotime($startTime) <= strtotime($endTime)) {

                            $start_time = date("H:i:s", strtotime($startTime));

                            $selectedTime[]= date("h:i a", strtotime($start_time));

                            $startTime = date("H:i:s",strtotime("+60 min", strtotime($start_time)));
                                          
                          }

                          foreach ($selectedTime as $time) {

                            if (lcfirst($selectedDay) ==  $interval['day'] && (strtotime($time) >= strtotime($interval['start_time']) && strtotime($time) <= strtotime($interval['end_time']))) {

                              $nonWorkingInterval = true;

                            }
                          } 

                        }

                    }
                
               }

            if ($postdata['data']['check_in']=='' || $postdata['data']['check_out']=='') {

                $msg = __('Please select date.');

            } else {

              if  ($nonWorkingDateFlag) {

               
                  $msg = __('Booking not available for selected date.Please check availability in calendar.');

               } elseif ($nonWorkingFullDayFlag) {
                
                  $msg = __('Booking not available for selected day.Please check availability in calendar.');
                   
               } elseif (isset($nonWorkingInterval) && $nonWorkingInterval) {

                  $msg = __('Booking not available for selected time.Please check availability in calendar.');
                    
                
               } else {

                $date11 = date('Y-m-d H:i:s', strtotime($postdata['data']['check_in']));
                $date22 = date('Y-m-d H:i:s', strtotime($postdata['data']['check_out']));
                
                $bookedRentProductCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('product_id',$postdata['data']['product_id'])->addFieldToFilter('status',['in'=>$status])->addFieldToFilter('booking_start_date',['from' => $date11, 'to' => $date22])->addFieldToFilter('booking_end_date',['from' => $date11, 'to' => $date22]);
                $bookedStartDateTime = $bookedRentProductCollection->getColumnValues('booking_start_date');
                $bookedEndDateTime = $bookedRentProductCollection->getColumnValues('booking_end_date');
                $bookedRentQty = $bookedRentProductCollection->getColumnValues('qty');

                $bookedDaysQty = 0;

                    if (count($bookedRentQty)==1) {
                      $bookedDaysQty = $bookedRentQty[0];
                    } elseif (count($bookedRentQty) > 1) {
                        foreach ($bookedRentQty as $orderedQty) {
                          $bookedDaysQty = $orderedQty + $bookedDaysQty;
                        }
                    }

                    $stockQty = $product->getStockQtyForAInterval();

                    $leftQty = $stockQty - $bookedDaysQty;

                    $postFromTime = explode(' ',$postdata['data']['check_in']);
                    $postToTime = explode(' ',$postdata['data']['check_out']); 
                    $dateDiff = strtotime($postToTime[0]) - strtotime($postFromTime[0]);

                    $fromTime = $postFromTime[1].' '.$postFromTime[2];
                    $toTime = $postToTime[1].' '.$postToTime[2];


                    if ((strtotime($postFromTime[0]) == strtotime($postToTime[0])) && strtotime($fromTime) >= strtotime($toTime)) {

                      $msg = __('For this time, service start date and service end date could not be same.');
                        
                    } else {

                      if (count($product)!=0) {

                        if (strtotime($fromTime) < strtotime($product->getServiceStartTime()) || strtotime($toTime) > strtotime($product->getServiceEndTime())) {

                          $msg = __('service starts from ').$product->getServiceStartTime().__(' to ').$product->getServiceEndTime();
                        } else {

                            if ($leftQty!=0 && $postdata['data']['qty'] > $leftQty) { 

                              $msg = __('Available Quantity is ').$leftQty;

                            } elseif ($leftQty == 0) {

                                $msg = __('Available quantity from '.$bookedStartDateTime[0].' to '.$bookedEndDateTime[0].' is 0.');


                            } else {
                              //$days = floor($dateDiff/(60*60*24)) +1;
                              $timediff = (strtotime($toTime)-strtotime($fromTime))/(60*60);

                              if ($timediff > 0) {
                                $days = floor($dateDiff/(60*60*24)) +1;
                                $perdayhours = $timediff;
                                $hours = $perdayhours * $days;
                               
                              } else {
                                $days = floor($dateDiff/(60*60*24));
                                $perdayhours = 24 + $timediff;
                                $hours = $perdayhours * $days;
                              }
                              
                              if ($hours < $product->getMinBookingHours() || $hours > $product->getMaxBookingHours()) {

                                $MinhrText = $product->getMinBookingHours() > 1 ? 'hours' : 'hour';
                                $MaxhrText = $product->getMaxBookingHours() > 1 ? 'hours' : 'hour';
                                $msg = 'You can not book less than '.$product->getMinBookingHours().' '.$MinhrText.' and greather than '.$product->getMaxBookingHours().' '.$MaxhrText;
                              } else {
                                $totaldays = $days;
                                $totalhours = number_format($perdayhours * $totaldays,2);
                                $totalprice = $totaldays * $totalhours * $postdata['data']['price'];
                              }
                            }
                            
                        }
                      }
                    }
                  }
                }
            // foreach ($bookedDates as $key=>$bookqty) {

            //   if ($bookqty!=0) {

            //     if ($bookqty >= $stockQty) {
            //         $bookDateArray[] = $key;
            //     } else {
            //       $bookedQty[] = $bookqty;
            //     }

                
            //   }
            // }
        }


            // $StockState = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
            //   $stockqty = $StockState->getStockQty($product->getId(), $product->getStore()->getWebsiteId()); 
            // if ($postdata['data']['rent']=='daily') {
              
            // } elseif ($postdata['data']['rent']=='hourly') {
              
            // }
            

            

          
            

            
             //} elseif ($postdata['data']['rent']=='hourly') {

                
                // $from_time_h = $postdata['data']['from_time_h']<10 ? '0'.$postdata['data']['from_time_h'] : $postdata['data']['from_time_h'];
                // $from_time_m = $postdata['data']['from_time_m'] <10 ? '0'.$postdata['data']['from_time_m'] : $postdata['data']['from_time_m'];
                // $to_time_h = $postdata['data']['to_time_h']<10 ? '0'.$postdata['data']['to_time_h'] : $postdata['data']['to_time_h'];
                // $to_time_m = $postdata['data']['to_time_m'] <10 ? '0'.$postdata['data']['to_time_m'] : $postdata['data']['to_time_m'];
                // $from_time_t = $postdata['data']['from_time_t'] == 1 ? 'am' : 'pm';
                // $to_time_t = $postdata['data']['to_time_t'] == 1 ? 'am' : 'pm';
                
          //     }
          // }  else {
               
          // }

        $data = ['msg'=>$msg,'totaldays'=>$totaldays,'totalprice'=>$totalprice,'totalhours'=>$totalhours] ;
        return $data;
  }
}
 