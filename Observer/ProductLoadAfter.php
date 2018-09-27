<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;

class ProductLoadAfter implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
	/**
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	 function __construct(
			\Magento\Framework\ObjectManagerInterface $objectManager,
	 		RequestInterface $request,
			ScopeConfig $scopeConfig,
			\Magento\Framework\Registry $registry,
			\Magento\Customer\Model\Session $customerSession,
			Product $catelogproductmodel,
			\Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
			\Magento\Framework\Message\ManagerInterface $messageManager
	) {
	 	
		$this->_objectManager=$objectManager;
		$this->scopeConfig = $scopeConfig;
		$this->registry = $registry;
		$this->request = $request;
		$this->customerSession = $customerSession;
		$this->_catalogproduct = $catelogproductmodel;
		$this->eavAttribute = $eavAttribute;
		$this->messageManager = $messageManager;
	}

	/**
	 * customer register event handler
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$product = $observer->getEvent()->getForm();
		$productdata = $observer->getEvent()->getProduct();

		//$post = $this->request->getPost();
		//print_r($post); die;

		if ($productdata->getTypeId() == 'booking') {

			$event = $observer->getEvent();
	    	$product = $event->getProduct();
	    	$product->lockAttribute('booking_check_in_time');
	    	$product->lockAttribute('booking_check_out_time');
			
		} 
	
	}

	// public function lockAttributes($observer) {
	//     $event = $observer->getEvent();
	//     $product = $event->getProduct();
	//     $product->lockAttribute('booking_check_in_time');
	//     $product->lockAttribute('booking_check_out_time');
	// } 

}


	
