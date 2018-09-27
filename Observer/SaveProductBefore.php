<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;

class SaveProductBefore implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
	protected $_dateTime;
	protected $jsonHelper;
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
			\Magento\Framework\Message\ManagerInterface $messageManager,
			\Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
			\Magento\Framework\Json\Helper\Data $jsonHelper
	) {
	 	
		$this->_objectManager=$objectManager;
		$this->scopeConfig = $scopeConfig;
		$this->registry = $registry;
		$this->request = $request;
		$this->customerSession = $customerSession;
		$this->_catalogproduct = $catelogproductmodel;
		$this->eavAttribute = $eavAttribute;
		$this->messageManager = $messageManager;
		$this->jsonHelper = $jsonHelper;
		$this->_dateTime = $dateTime;
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

		$post = $this->request->getPost();

		if ($productdata->getTypeId() == 'booking') {

			if (isset($post['product']['stock_qty_for_a_interval']) && $post['product']['stock_qty_for_a_interval'] > $post['product']['quantity_and_stock_status']['qty'])
			{
				throw new \Magento\Framework\Exception\LocalizedException(__('Stock quantity for a interval should less than the product quantity'));
			}
			elseif (isset($post['product']['stock_qty_for_a_day']) && $post['product']['stock_qty_for_a_day'] > $post['product']['quantity_and_stock_status']['qty'])
			{
				throw new \Magento\Framework\Exception\LocalizedException(__('Stock quantity for a day should less than the product quantity'));
			}
			elseif (isset($post['product']['min_booking_hours']) && isset($post['product']['max_booking_hours']) && $post['product']['min_booking_hours'] > $post['product']['max_booking_hours'])
			{
				throw new \Magento\Framework\Exception\LocalizedException(__('Minimum booking hours should be less than the maximum booking hours'));
			}
			elseif (isset($post['product']['booking_min_days']) && isset($post['product']['booking_max_days']) && $post['product']['booking_min_days'] > $post['product']['booking_max_days'])
			{
				throw new \Magento\Framework\Exception\LocalizedException(__('Minimum booking days should be less than the maximum booking days'));
			} else {
				if (isset($post['product']['non_working_days'])) {

					$response = $post['product']['non_working_days'];

					foreach ($response as $key=>$res) {
						$response[$key]['option_id'] = $res['record_id'];
						if (isset($res['is_delete']) && $res['is_delete'] == 1) {
							unset($response[$key]);
						}
						$res1 = array_values($response);
					}

					array_walk_recursive( $res1,
		              function(&$v, $k) { 


			              	if($k == 'start_date' || $k == 'end_date'){
			              		$v = $this->_dateTime->date(null, $v);
			              	}

			              	if($k == 'appointment_start_time' || $k == 'appointment_end_time'){
			              		if(strlen($v)>8)
			              			$v = substr($v,-8);
			              	}
		              	

		              	}
		              );
			    	$encodedData = $this->jsonHelper->jsonEncode($res1);
					$productdata->setData('non_working_days',$encodedData);
				}
			}
			$prevCat= '';
			$prevType = '';
			if ($post[0]) {
				foreach ($post[0] as $data) {

					if ($data['id'] == '0') {

						if ($data['category'] == $prevCat && $data['type'] == $prevType) {

							throw new \Magento\Framework\Exception\LocalizedException(__('Cannot add multiple rooms with the same type.'));

						}
						$prevCat =  $data['category'];
						$prevType = $data['type'];
					}
					
				}
			}
			$productdata->setData('map_location',$post['booking_map_location'])
						->setData('map_lat',$post['booking_map_lat'])
						->setData('map_lon',$post['booking_map_lon']);
		} 
	
	} 

}


	
