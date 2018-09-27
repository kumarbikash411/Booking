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

namespace Ced\Booking\Controller\Adminhtml\Dashboard;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action;


class BookingStatusCalendar extends Action
{
   
    
    /**
     * @param execute
     */
    public function execute()
    {
        $start_date =  $this->getRequest()->getPost('start');
        $end_date = $this->getRequest()->getPost('end');

        $product = $this->_objectManager->create('Magento\Catalog\Model\Product');

        $roomCollection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection();
       /* $roomCollection->getSelect()->join(
               ['order_grid'=>$roomCollection->getTable('sales_order_grid')],
               'main_table.order_id = order_grid.entity_id',
               ['increment_id'=>'order_grid.increment_id']);

        $roomCollection->getSelect()->join(
               ['cs_order'=>$roomCollection->getTable('ced_csmarketplace_vendor_sales_order')],
               'order_grid.increment_id = cs_order.order_id',
               ['cs_order_id'=>'cs_order.id', 'cs_vendor_id' =>'cs_order.vendor_id']);
    */

        



        $rentCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection();
       /* $rentCollection->getSelect()->join(
               ['order_grid'=>$rentCollection->getTable('sales_order_grid')],
               'main_table.order_id = order_grid.entity_id',
               ['increment_id'=>'order_grid.increment_id']);

        $rentCollection->getSelect()->join(
               ['cs_order'=>$rentCollection->getTable('ced_csmarketplace_vendor_sales_order')],
               'order_grid.increment_id = cs_order.order_id',
               ['cs_order_id'=>'cs_order.id', 'cs_vendor_id' =>'cs_order.vendor_id']);
*/




        $events = [];
        foreach ($roomCollection as $order) {
            $start = explode(' ',$order->getBookingStartDate());
            $end = explode(' ',$order->getBookingEndDate());
            $events[] = ['title' => $order->getOrderId().' Booked', 'start' => $start[0], 'end' => $end[0], 'booking_order_id' => $order->getId(), 'order_type' => 'room'];
        }


        foreach ($rentCollection as $order) {
            $start = explode(' ',$order->getBookingStartDate());
            $end = explode(' ',$order->getBookingEndDate());
             $events[] = ['title' => $order->getOrderId().' Booked', 'start' => $start[0], 'end' => $end[0], 'booking_order_id' => $order->getId(), 'order_type' => 'rent'];
        }
        
        $resultJsonFactory = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\Controller\Result\JsonFactory');
        
        $resultJson =  $resultJsonFactory->create();

        return $resultJson->setData($events);

    }
}
