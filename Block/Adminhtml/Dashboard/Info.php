<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Booking
 * @author 		CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block\Adminhtml\Dashboard;
use Magento\Backend\Block\Template;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
class Info extends Template
{
	
	/**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
	public $_localeCurrency;

    function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		Timezone $timezone,
		PriceHelper $priceHelper,
		\Magento\Framework\objectManagerInterface $_objectManager,
		\Magento\Sales\Model\Order\Item $itemCollection,
		\Magento\Sales\Model\ResourceModel\Order\Grid\CollectionFactory $collection,
	   	\Magento\Framework\Locale\Currency $localeCurrency,

		array $data = []
	)
	{
		$this->_timezone = $timezone;
		$this->_objectManager = $_objectManager;
		$this->_priceHelper = $priceHelper;
		$this->itemCollection = $itemCollection;
		$this->collection = $collection;
		$this->_localeCurrency = $localeCurrency;

		parent::__construct($context, $data);
	}
	
	/**
     * Get pending amount data
     *
     * @return Array
     */
	 public function getPendingAmount() {

		// Total Pending Amount
		$pendingAmount = 0;
		$pendingOrdersCollection = [];
		$data = ['total'=> $pendingAmount , 'action' => ''];
		$priceCurrency=$this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
		$orderCollection  = $this->getBookingOrdersCollection();
		if (count($orderCollection)!=0) {
			$orderData = $orderCollection->addFieldToFilter('status','pending')->getData();
		}
		if (isset($orderData) && count($orderData)!=0) {
			foreach ($orderData as $pendingData) {
				$pendingAmount = $pendingData['grand_total'] + $pendingAmount;
			}	
		}

		if ($pendingAmount > 1000000000000) {
				$pendingAmount = round($pendingAmount / 1000000000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($pendingAmount) . 'T';
	
		} elseif ($pendingAmount > 1000000000) {
				$pendingAmount = round($pendingAmount / 1000000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($pendingAmount) . 'B';
			
		} elseif ($pendingAmount > 1000000) {
				$pendingAmount = round($pendingAmount / 1000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($pendingAmount) . 'M';
			
		} elseif ($pendingAmount > 1000) {
				$pendingAmount = round($pendingAmount / 1000, 4);	
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($pendingAmount) . 'K';
		
		} else {
				$data['total'] =$priceCurrency->format($pendingAmount);
		}
			 
				
		return $data;
	}
	
	/**
     * Get admin's Earned Amount data
     *
     * @return Array
     */
	public function getEarnedAmount() {
		
		// Total Earned Amount
		$data = ['total'=> 0 , 'action' => ''];
		$completeOrdersCollection = [];
		$netAmount = 0;
		$priceCurrency=$this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
		$orderCollection  = $this->getBookingOrdersCollection();
		if (count($orderCollection)!=0) {
			$orderData = $orderCollection->addFieldToFilter('status','complete')->getData();
		}
		if (isset($orderData) && count($orderData)!=0) {
			foreach ($orderData as $earnedData) {
				$netAmount = $earnedData['grand_total'] + $netAmount;
			}
		}
		
		if ($netAmount > 1000000000000) {
				$netAmount = round($netAmount / 1000000000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($netAmount) . 'T';
				
		} elseif ($netAmount > 1000000000) {
				$netAmount = round($netAmount / 1000000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($netAmount) . 'B';
		} elseif ($netAmount > 1000000) {
				$netAmount = round($netAmount / 1000000, 4);
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($netAmount) . 'M';
			
		} elseif ($netAmount > 1000) {
				$netAmount = round($netAmount / 1000, 4);	
				$data['total'] = $this->_localeCurrency->getCurrency($this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore(null)->getBaseCurrencyCode())->toCurrency($netAmount) . 'K';
							
		} else {
			
			
				$data['total'] = $priceCurrency->format($netAmount);
		}
			
		return $data;
	}

	public function getBookingOrdersCollection()
	{
		$itemscollection = $this->itemCollection->getCollection()->addFieldToFilter('product_type','booking');
        
        $orderId = $itemscollection->getColumnValues('order_id');
        $unique_order_id = array_unique($orderId);
        
        $collection = $this->collection->create();
        if (count($collection)!=0) {
        	$ordersCollection = $collection->addFieldToFilter('entity_id', $unique_order_id);
        } else {
        	$ordersCollection = [];
        }
        return $ordersCollection;
	}
	
	/**
     * Get vendor's Orders Placed data
     *
     * @return Array
     */
	public function getOrdersPlaced() {
		// Total Orders Placed
		$data = ['total'=> 0 , 'action' => ''];
		
        
		$order_total = count($this->getBookingOrdersCollection());

		if ($order_total > 1000000000000) {
			$data['total'] = round($order_total / 1000000000000, 1) . 'T';
		} elseif ($order_total > 1000000000) {
			$data['total'] = round($order_total / 1000000000, 1) . 'B';
		} elseif ($order_total > 1000000) {
			$data['total'] = round($order_total / 1000000, 1) . 'M';
		} elseif ($order_total > 1000) {
			$data['total'] = round($order_total / 1000, 1) . 'K';						
		} else {
			$data['total'] = $order_total;
		}
					
		return $data;
	}
	
	/**
     * Get Products Sold data
     *
     * @return Array
     */
	 public function getProductsSold() {
		// Total[ Products Sold

		$itemscollection = $this->itemCollection->getCollection()->addFieldToFilter('product_type','booking');
        $qtyOrdered = $itemscollection->getColumnValues('qty_ordered');
        


		$data = ['total'=> 0 , 'action' => ''];
		$data['total'] =  array_sum($qtyOrdered);
		return $data;
	}

}
