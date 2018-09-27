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
* @author      CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>
* @copyright   Copyright CedCommerce (http://cedcommerce.com/)
* @license      http://cedcommerce.com/license-agreement.txt
*/ 
 
namespace Ced\Booking\Controller\Adminhtml\Productsave;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ProductRoomview extends Action
{
	 /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * Result page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
	 
    protected $_resultPageFactory;
	 /**
     * Result page factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory;
     */
	protected $_resultJsonFactory;
	 
	function __construct
	(
		Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory
	)
	{
		parent::__construct($context);
		$this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
	}
	public function execute()
	{
		$sku = $this->_request->getParam('sku');
		$product_id = $this->_request->getParam('product_id');
		$newaddreq = $this->_request->getParam('newaddreq');
		$roomId =  $this->_request->getParam('room_id');

		//$roomBookingId = $this->_request->getParam('room_booking_id',0);
		$resultPage= $this->_resultPageFactory->create();
		$resultJson = $this->_resultJsonFactory->create();
		$passArray =['sku'=>$sku ,'newaddreq' => $newaddreq,'room_id'=>$roomId,'product_id'=>$product_id];
		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\ProductSavePopup')
		->setData($passArray)->setTemplate('Ced_Booking::catalog/product/room_save_popup.phtml')
			->toHtml();

		$response = array('template'=> $template);
		return $resultJson->setData($response);
	}

}