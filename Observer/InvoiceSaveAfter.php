<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order\Address\Renderer;

class InvoiceSaveAfter implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
  protected $_messageManager;

  const ORDER_STATUS_COMPLETE = 'complete';
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
	 * customer register event handler
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{

        $invoicedata = $observer->getEvent()->getInvoice()->getOrder();
        $allItems = $invoicedata->getAllItems();
        $orderIncrementId = $invoicedata->getIncrementId();


	        foreach ($allItems as $item) {

	        	if ($item->getProductType() == 'booking') {

	        		if ($item->getQtyInvoiced() != '0.0000') {

			          	$productOptions = $item->getProductOptions();

				        $rentOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->getCollection()->addFieldToFilter('order_id',$orderIncrementId);

				        if (count($rentOrderCollection)!=0) {

				            foreach ($rentOrderCollection as $rent) {

				              $startDate = explode(' ',$rent->getBookingStartDate());
				              $endDate = explode(' ',$rent->getBookingEndDate());

				              if ($productOptions['info_buyRequest']['booking-type'] == 'rent_hourly') {

				              	  if (strtotime($rent->getBookingStartDate()) == strtotime($productOptions['info_buyRequest']['check-in']) && strtotime($rent->getBookingEndDate()) == strtotime($productOptions['info_buyRequest']['check-out'])) {

				              	  		$rentOrderModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->load($rent->getId());

				                  		$rentOrderModel->setData('status',static::ORDER_STATUS_COMPLETE);
				                  		$rentOrderModel->save();

				              	  }
				              	 
				              } elseif ($productOptions['info_buyRequest']['booking-type'] == 'rent_daily') {


				              	if ($productOptions['info_buyRequest']['check-in'] == $startDate[0] && $productOptions['info_buyRequest']['check-out'] == $endDate[0]) {

				                  	$rentOrderModel = $this->_objectManager->create('Ced\Booking\Model\RentTypeProductOrders')->load($rent->getId());

				                  	$rentOrderModel->setData('status',static::ORDER_STATUS_COMPLETE);
				                  	$rentOrderModel->save();
				             	}
				              }

				              	
				            }
			          	}

			          	$roomOrderCollection = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->getCollection()->addFieldToFilter('order_id',$orderIncrementId);

			          	if (count($roomOrderCollection)!=0) {

				            foreach ($roomOrderCollection as $room) {

				              $startDate = explode(' ',$room->getBookingStartDate());
				              $endDate = explode(' ',$room->getBookingEndDate());

				              	if (isset($productOptions['info_buyRequest']['check-in']) && $productOptions['info_buyRequest']['check-in'] == $startDate[0] && isset($productOptions['info_buyRequest']['check-out']) && $productOptions['info_buyRequest']['check-out'] == $endDate[0]) {

				                	$roomOrderModel = $this->_objectManager->create('Ced\Booking\Model\RoomOrder')->load($room->getId());
				                	$roomOrderModel->setData('status',static::ORDER_STATUS_COMPLETE)->save();
				              	}
				            }
			          	}
			        } 
			    }
			}
	}

}


	
