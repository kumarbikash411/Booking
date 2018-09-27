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

class ProductRoomDelete extends \Magento\Backend\App\Action
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
		
		$roomId = $this->getRequest()->getPost('room_id');
		$model = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($roomId);
		try{
		$model->delete();
		$resultJson = $this->_resultJsonFactory->create();

		//$response = array('mesg'=> 'Room deleted successfully');
		}catch(\Exception $e)
		{
			$response = array('mesg'=> $e);
			return $resultJson->setData($response);
		}
		$resultPage = $this->_resultPageFactory->create();
		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Product\Edit\Tab')->setTemplate('Ced_Booking::catalog/product/edit.phtml')
			->toHtml();

			$response = array('template'=> $template);
		return $resultJson->setData($response);
		

	}

}