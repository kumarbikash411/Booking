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

class ProductRoomClose extends \Magento\Backend\App\Action
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
		$params = $this->getRequest()->getParams();

		$sku = $params['room_product_sku'];

		// $facilities=$this->_objectManager->create ( 'Ced\Booking\Model\Facilities')->getCollection();

		$resultPage =  $this->_resultPageFactory->create();

		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Product\Edit\Tab')->setData(['sku'=>$sku])
		->setTemplate('Ced_Booking::catalog/product/edit.phtml')->toHtml();

        $response = ['template'=> $template];
        $resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
	}

}