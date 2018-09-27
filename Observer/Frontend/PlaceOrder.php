<?php

namespace Ced\Booking\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Cart as BookingCoreCart;

class PlaceOrder implements ObserverInterface
{
    protected $customerSession;

    const ORDER_STATUS_PENDING = 'pending';
    
    public function __construct(
                RequestInterface $request,
                BookingCoreCart $bookingCoreCart,
                \Magento\Customer\Model\Session $customerSession,
                \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
                \Magento\Framework\Message\ManagerInterface $messageManager,
                \Magento\Framework\UrlInterface $url,
                \Magento\Framework\App\ResponseFactory $responseFactory

            )
    {

        $this->_request = $request;
        $this->_bookingCoreCart = $bookingCoreCart;
        $this->customerSession = $customerSession;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_stockRegistry = $stockRegistry;
        $this->messageManager = $messageManager;
        $this->_url = $url;
        $this->_responseFactory = $responseFactory;
    }
    public function execute(EventObserver $observer)
    {
        $productType = [];
        $booking_items = [];
        $order = $observer->getEvent()->getOrder();
        $customer = $observer->getEvent()->getCustomer();
        $customerInfo = $order->getBillingAddress();
        foreach($order->getAllItems() as $items) {

            $booking_items[] = $items->getData();
            $productOptions = $items->getProductOptions();
            $productType[] = $items->getProductType();

            if ($items->getProductType() == 'booking') { 

                if ($productOptions['info_buyRequest']['booking-type'] == 'hotel') {

                    $orderedRoomsCollection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('product_id',$items->getProductId())->addFieldToFilter('booking_start_date',$productOptions['info_buyRequest']['check-in'])->addFieldToFilter('booking_end_date',$productOptions['info_buyRequest']['check-out'])->addFieldToFilter('room_id',$productOptions['info_buyRequest']['room_id'])->getColumnValues('room_number_id');
                    if (count($orderedRoomsCollection)!=0) {

                        foreach ($orderedRoomsCollection as $roomNumberIds) {

                            $roomNumbersCollection = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->getCollection()->addFieldToFilter('room_id',$productOptions['info_buyRequest']['room_id'])->addFieldToFilter('id',['neq'=>$roomNumberIds])->getColumnValues('id');
                        }
                    } else {
                            $roomNumbersCollection = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->getCollection()->addFieldToFilter('room_id',$productOptions['info_buyRequest']['room_id'])->getColumnValues('id');
                    }
                    for ($i=0; $i<$items->getQtyOrdered() ; $i++) {
                        try {
                            $roomOrderModel = $this->_objectManager->create('Ced\Booking\Model\RoomOrder');
                            $roomOrderModel->setData('product_id',$items->getProductId())
                                           ->setData('order_id',$order->getIncrementId())
                                           ->setData('room_id',$productOptions['info_buyRequest']['room_id'])
                                           ->setData('room_number_id',$roomNumbersCollection[$i])
                                           ->setData('booking_start_date',$productOptions['info_buyRequest']['check-in'])
                                           ->setData('booking_end_date',$productOptions['info_buyRequest']['check-out'])
                                           ->setData('total_days',$productOptions['info_buyRequest']['total_days'])
                                           ->setData('status',static::ORDER_STATUS_PENDING);
                            $roomOrderModel->save();
                        } catch (\Exception $e) {
                            var_dump($e->getMessage()); die;
                        }
                    }

                } elseif ($productOptions['info_buyRequest']['booking-type'] == 'rent_daily' || $productOptions['info_buyRequest']['booking-type'] == 'rent_hourly') {

                    $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($items->getProductId());
                    
                    $stockItem=$this->_stockRegistry->getStockItem($items->getProductId()); 
                    $stockItem->setData('qty',($stockItem->getQty() +$items->getQtyOrdered())); 
                    $stockItem->setData('use_config_notify_stock_qty',1);
                    $stockItem->save(); 
                   
                    try {
                        $rentOrderModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders');
                        $rentOrderModel->setData('order_id',$order->getIncrementId())
                                   ->setData('product_id',$items->getProductId())
                                   ->setData('booking_start_date',$productOptions['info_buyRequest']['check-in'])
                                   ->setData('booking_end_date',$productOptions['info_buyRequest']['check-out'])
                                   ->setData('total_days',$productOptions['info_buyRequest']['total-days'])
                                   ->setData('qty',$items->getQtyOrdered())
                                   ->setData('status',static::ORDER_STATUS_PENDING);
                        $rentOrderModel->save(); 
                    } catch (\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
                    }
                } elseif ($productOptions['info_buyRequest']['booking-type'] == 'appointment') {
                   
                    try {
                        $rentOrderModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders');

                        $rentOrderModel->setData('order_id',$order->getIncrementId())
                                   ->setData('product_id',$items->getProductId())
                                   ->setData('booking_start_date',$productOptions['info_buyRequest']['appointment_selected_date'].' '.date('H:i:s',strtotime(substr($productOptions['info_buyRequest']['selected_slots'],0,8))))
                                   ->setData('booking_end_date',$productOptions['info_buyRequest']['appointment_selected_date'].' '.date('H:i:s',strtotime(substr($productOptions['info_buyRequest']['selected_slots'],10,10))))
                                   ->setData('qty',$items->getQtyOrdered())
                                   ->setData('status',static::ORDER_STATUS_PENDING);
                        $rentOrderModel->save(); 
                    } catch (\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
                    }
                }
            }
           
        } 
        // if (in_array('booking',$productType)) {

        //     $roomOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('order_id',$order->getIncrementId());
        //     $rentOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('order_id',$order->getIncrementId());

        //     if (count($roomOrderCollection)!=0 && count($rentOrderCollection)!=0) {
        //         $booking_items = array_merge($roomOrderCollection->getData(),$rentOrderCollection->getData());
        //     } elseif (count($rentOrderCollection)!=0) {
        //         $booking_items = $rentOrderCollection->getData();
        //     } elseif (count($roomOrderCollection)!=0) {
        //         $booking_items = $roomOrderCollection->getData();
        //     }
        //     $emailHelper = $this->_objectManager->create('Ced\Booking\Helper\SendEmailAfterPlaceOrder');
        //     $emailHelper->Sendmailtocustomer($booking_items,$order); 
        //     $emailHelper->Sendmailtoadmin($booking_items,$order);  
        // }
    }

}
