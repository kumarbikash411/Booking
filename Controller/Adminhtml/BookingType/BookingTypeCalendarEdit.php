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

namespace Ced\Booking\Controller\Adminhtml\BookingType;



use Magento\Backend\App\Action\Context;

use Magento\Framework\View\Result\PageFactory;

use Magento\Backend\App\Action;



class BookingTypeCalendarEdit extends \Magento\Backend\App\Action

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

		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {



    	parent::__construct($context);

    	$this->resultPageFactory = $resultPageFactory;

    	$this->_resultJsonFactory = $resultJsonFactory;

    	$this->_objectManager = $context->getObjectManager();

    }

	

	/**

	 * Index action

	 *

	 * @return void

	 */

	public function execute()
	{
		
        $sku = $this->_request->getParam('sku');
		$bookingType = $this->_request->getParam('booking_type','hotel');
		$resultJson = $this->_resultJsonFactory->create();
		/*$dataSend = ['booking_id'=>$bookingId,
			         'booking_type'=>$bookingType];*/
	    $resultpage = $this->resultPageFactory->create();
		$htmlCalendarForm = $resultpage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\BookingTypeRent')->setTemplate('Ced_Booking::catalog/product/bookingtype/booking_type_form.phtml')->toHtml();
		$response = ['html_calendar_form'=> $htmlCalendarForm];
		return $resultJson->setData($response);
    
	}

}