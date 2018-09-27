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
namespace Ced\Booking\Block\Adminhtml\Dashboard;


class BookingDetails extends \Magento\Backend\Block\Template
{

    protected  $_helperData;
    protected $orderRepository;

    public function __construct(

        \Ced\Booking\Helper\Data $helperData,
        \Magento\Backend\Block\Widget\Context $context,
         \Magento\Sales\Api\Data\OrderInterface $order,

        array $data = array())
    {
        $this->orderRepository = $order;
        $this->_helperData = $helperData;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        parent::__construct($context, $data);

    }


    public function getOrderDetails(){

        $bookingOrderId = $this->getOrderId();
        $orderType = $this->getOrderType();
        $data = [];
        if($orderType == 'room'){
            $bookingModel = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->load($bookingOrderId);

        }else{
            $bookingModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->load($bookingOrderId);
        }
        $orderModel = $this->orderRepository->loadByIncrementId($bookingModel->getOrderId());
        $productModel =  $this->_objectManager->create('Magento\Catalog\Model\Product')->load($bookingModel->getProductId());

        $data['order_entity_id']       =$orderModel->getId();
        $data['order_id'] = $bookingModel->getOrderId();
        $data['order_created_date'] = $orderModel->getcreatedAt();
        $data['product_name'] = $productModel->getName();
        $data['booking_from'] = $bookingModel->getBookingStartDate();
        $data['booking_to'] = $bookingModel->getBookingEndDate();
        if ($orderType == 'room')
        {
            $data['booking_qty'] = count($bookingModel->getData());
        } else {
            $data['booking_qty'] = $bookingModel->getQty();
        }
        
        $data['booking_status'] = $orderModel->getStatus();
       /* $vOrderCollection = $this->_objectManager->create('Ced\CsMarketplace\Model\Vorders')->getCollection()->addFieldToFilter('order_id', $bookingModel->getOrderId());


        $data['cs_order_id'] = $vOrderCollection->getFirstItem()->getId();*/


        $data['product_id'] = $productModel->getId();
        $data['product_attribute_set_id'] = $productModel->getAttributeSetId();

        return $data;
    }
}
