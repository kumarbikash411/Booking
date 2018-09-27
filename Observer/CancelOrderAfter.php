<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order\Address\Renderer;

class CancelOrderAfter implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
  	protected $_messageManager;

  const ORDER_STATUS_CANCEL = 'cancelled';
	/**
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	 function __construct(
			\Magento\Framework\ObjectManagerInterface $objectManager,
	 		RequestInterface $request,
			ScopeConfig $scopeConfig,
			\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
      		Renderer $addressRenderer,
      		\Magento\Framework\Message\ManagerInterface $messageManager

	) {
	 	
		$this->_objectManager=$objectManager;
		$this->scopeConfig = $scopeConfig;
		$this->request = $request;
		$this->_transportBuilder = $transportBuilder;
    	$this->addressRenderer = $addressRenderer;
    	$this->_messageManager = $messageManager;

	}

	/**
	 * cancel order event handler
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $order = $observer->getEvent()->getOrder();

  		$roomOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('order_id',$order->getIncrementId());
        if (count($roomOrderCollection)!=0) {
        	foreach ($roomOrderCollection as $room) {
        		$roomModel = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->load($room->getId());
        		$roomModel->setData('status',static::ORDER_STATUS_CANCEL);
        		$roomModel->save();
        	}
            
        }
        $rentOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('order_id',$order->getIncrementId());
        if (count($rentOrderCollection)!=0) {
        	foreach ($rentOrderCollection as $rent) {
            	$rentModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->load($rent->getId());
            	$rentModel->setData('status',static::ORDER_STATUS_CANCEL);
            	$rentModel->save();
            }
        }
	}

}


	
