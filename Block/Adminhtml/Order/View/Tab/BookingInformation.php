<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Booking
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Block\Adminhtml\Order\View\Tab;


class BookingInformation extends \Magento\Sales\Block\Adminhtml\Order\AbstractOrder implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{   

    /**
     * booking information template
     *
     * @var string
     */
    public $_template = 'order/booking/booking_info.phtml';


    /**
     * Get ObjectManager instance
     *
     * @return \Magento\Framework\App\ObjectManager
     */
    public function getObjectManager()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager;
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    public function getroomOrderItems()
    {
        $roomOrderCollection = $this->getObjectManager()->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('order_id',$this->getOrder()->getIncrementId()); 

        return $roomOrderCollection;

        // $orderItems = $this->getObjectManager()->get('Magento\Sales\Model\ResourceModel\Order\Item\Collection')->addFieldToFilter('order_id',$this->getOrder()->getId());
        // return $orderItems;
    }
    public function getrentOrderItems()
    {
        $rentOrderCollection = $this->getObjectManager()->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('order_id',$this->getOrder()->getIncrementId()); 
        
        return $rentOrderCollection;

        // $orderItems = $this->getObjectManager()->get('Magento\Sales\Model\ResourceModel\Order\Item\Collection')->addFieldToFilter('order_id',$this->getOrder()->getId());
        // return $orderItems;
    }

    public function getProduct()
    {
        return $this->getObjectManager()->create('Magento\Catalog\Model\Product');
    }

    public function getBookedRoomNumbers()
    {
        $roomOrderCollection = $this->getObjectManager()->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('order_id',$this->getOrder()->getIncrementId());
        $roomNumberIds = $roomOrderCollection->getColumnValues('room_number_id');

        if (count($roomOrderCollection)!=0) {
            $roomNumbersCollection = $this->getObjectManager()->create('Ced\Booking\Model\RoomNumbers')->getCollection()->addFieldToFilter('id',$roomNumberIds);
            $roomNumber = $roomNumbersCollection->getColumnValues('room_numbers');
            return array_unique($roomNumber);
        } else {
            return '';
        }
    } 

    /**
     * Retrieve source model instance
     *
     * @return \Magento\Sales\Model\Order
     */

    public function getSource()
    {
        return $this->getOrder();
    }

    /**
     * Retrieve order totals block settings
     *
     * @return array
     */

    public function getOrderTotalData()
    {
        return [
            'can_display_total_due' => true,
            'can_display_total_paid' => true,
            'can_display_total_refunded' => true
        ];
    }

    /**
     * Get order info data
     *
     * @return array
     */

    public function getOrderInfoData()
    {
        return ['no_use_order_link' => true];
    }

    /**
     * Get tracking html
     *
     * @return string
     */

    public function getTrackingHtml()
    {
        return $this->getChildHtml('order_tracking');
    }

    /**
     * Get items html
     *
     * @return string
     */

    public function getItemsHtml()
    {
        return $this->getChildHtml('order_items');
    }

    /**
     * Retrieve gift options container block html
     *
     * @return string
     */

    public function getGiftOptionsHtml()
    {
        return $this->getChildHtml('gift_options');
    }

    /**
     * Get payment html
     *
     * @return string
     */

    /*public function getPaymentHtml()
    {
        return $this->getChildHtml('order_payment');
    }*/

    /**
     * View URL getter
     *
     * @param int $orderId
     * @return string
     */

    public function getViewUrl($orderId)
    {
        return $this->getUrl('sales/*/*', ['order_id' => $orderId]);
    }

    /**
     * ######################## TAB settings #################################
     */

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Booking Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Booking Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
        // $orderItems = $this->getOrderItems();
        // foreach ($orderItems as $item) {
        //     if ($item->getProductType() == 'booking') {
        //         $flag = true;
        //     }
        // }
        // if ($flag) 
        // {
        //     return true;
        // } else {
        //     return false;
        // }
        
        /*$data = $this->getModel();
        if (count($data) > 0) {
            return true;
        }else{
            return false;
        }*/

    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
       /* $data = $this->getModel();
        if (count($data) > 0) {
            return false;
        }else{
            return true;
        }*/
    }
}