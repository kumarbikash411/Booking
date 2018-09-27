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
 
namespace Ced\Booking\Controller\Adminhtml\ProductSave;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class BookingType extends \Magento\Backend\App\Action
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
	/**

	 * @var execute

	 */
	public function execute()
	{  
		$resultPage = $this->_resultPageFactory->create();
		$resultJson = $this->_resultJsonFactory->create();
		$bookingType = $this->_request->getParam('booking_type');
		$sku = $this->_request->getParam('sku');
		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\BookingTypeRent')->setData(['objectManager'=>$this->_objectManager,'sku'=>$sku])->setTemplate('Ced_Booking::catalog/product/bookingtype/booking_type_rent.phtml')
			->toHtml();
		$facility_template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\BookingTypeRent')->setData(['bookingType'=>$bookingType,'sku'=>$sku])->setTemplate('Ced_Booking::catalog/product/bookingtype/booking_type_rent_facilities.phtml')
			->toHtml();
		$response = ['template'=> $template,'facility_template'=>$facility_template];
		return $resultJson->setData($response);

	}

}