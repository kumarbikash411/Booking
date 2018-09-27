<?php

namespace Ced\Booking\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Cart as BookingCoreCart;

class PlaceOrderBefore implements ObserverInterface
{
    protected $customerSession;
    
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
        $order = $observer->getEvent()->getOrder();
        $customer = $observer->getEvent()->getCustomer();
        $customerInfo = $order->getBillingAddress();
        foreach($order->getAllItems() as $items) {

            $booking_items[] = $items->getData();
            $productOptions = $items->getProductOptions();
            
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
                    if (count($roomNumbersCollection) < $items->getQtyOrdered()) {
                        die("xszx");
                        $message = "Ordered quanity is not available for selected dates";
                        $CustomRedirectionUrl = $this->_url->getUrl('*/cart');
                        $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
                        $this->messageManager->addError($message);
                        exit;
                    } 
                } elseif ($productOptions['info_buyRequest']['booking-type'] == 'rent_daily') {
                    
                    $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($items->getProductId());
                    if ($items->getQtyOrdered() > $product->getStockQtyForADay())
                    {
                        $message = __("Ordered quanity is not available for selected dates");
                        $CustomRedirectionUrl = $this->_url->getUrl('*/cart');
                        $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
                        $this->messageManager->addError($message);
                        exit;
                    }
                } elseif($productOptions['info_buyRequest']['booking-type'] == 'rent_hourly') {
                    $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($items->getProductId());
                    if ($items->getQtyOrdered() > $product->getStockQtyForAInterval())
                    {
                        $message = "Ordered quanity is not available for selected dates";
                        $CustomRedirectionUrl = $this->_url->getUrl('*/cart');
                        $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
                        $this->messageManager->addError($message);
                        exit;
                    }
                }
            }
        } 
    }

}
