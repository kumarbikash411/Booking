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
  * @author      CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright   Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license     http://cedcommerce.com/license-agreement.txt
  */

namespace Ced\Booking\Controller\Adminhtml\Order;

class Index extends \Magento\Backend\App\Action
{	
	/*** @var \Magento\Framework\View\Result\PageFactory */    
	protected $resultPageFactory;	
	public function __construct(		
		\Magento\Backend\App\Action\Context $context,		
		\Magento\Framework\View\Result\PageFactory $resultPageFactory    
		) 
	{    parent::__construct($context);    	
		$this->resultPageFactory = $resultPageFactory;    	
	}


	/**	 
	 * Index action		 
	 * @return void	 
	 */	
	public function execute()	
	{			
		$resultPage = $this->resultPageFactory->create();			
		$resultPage->setActiveMenu('Ced_Booking::booking_orders');			
		$resultPage->addBreadcrumb(__('Add Order'), __('Booking Order'));			
		$resultPage->getConfig()->getTitle()->prepend(__('Booking Order'));			
		return $resultPage;			
	}	

	protected function _isAllowed()	{		
		return $this->_authorization->isAllowed('Ced_Booking::booking_orders');	
	}
}