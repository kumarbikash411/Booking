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

 * @author   	CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */

namespace Ced\Booking\Controller\Adminhtml\BookingProduct;



use Magento\Backend\App\Action\Context;

use Magento\Framework\View\Result\PageFactory;

use Magento\Backend\App\Action;

use Magento\Framework\Controller\Result\JsonFactory;



class ProductFacility extends \Magento\Backend\App\Action

{

	/**

     * @var \Magento\Framework\View\Result\PageFactory

     */

    protected $resultPageFactory;

	/**

     * $_objectManager

     *

     * @var \Magento\Framework\App\ObjectManager $objectManager

     */

    protected $_objectManager;



	public function __construct(

		\Magento\Backend\App\Action\Context $context,

		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory

    ) {



    	parent::__construct($context);

    	$this->resultPageFactory = $resultPageFactory;

    	$this->_objectManager =  $context->getObjectManager();

    	$this->_resultJsonFactory = $resultJsonFactory;

    }

	

	/**

	 * Index action

	 *

	 * @return void

	 */

	public function execute()
	{
			
			
			$booking_type = $this->getRequest()->getParam('booking_type');

			$resultPage = $this->resultPageFactory->create();

			$resultPage->setActiveMenu('Ced_Booking::booking_facilities');

			$resultPage->addBreadcrumb(__('Add Facilities'), __('Booking Facilities'));

			$resultPage->getConfig()->getTitle()->prepend(__('Booking Facilities'));

			$resultJson = $this->_resultJsonFactory->create();

			$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Product\Facility')->setTemplate('Ced_Booking::catalog/product/productfacilities.phtml')->toHtml();
			$response = ['template'=> $template];
			return $resultJson->setData($response);
		
	}

	protected function _isAllowed()
	{

		return $this->_authorization->isAllowed('Ced_Booking::booking_facilities');

	}

}