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
* @license     http://cedcommerce.com/license-agreement.txt
*/ 

namespace Ced\Booking\Controller\Adminhtml\Location;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
	/**
	 * @var \Magento\Backend\Model\View\Result\Forward
	 */
	protected $_objectManager;
	
	/**
	 * @var \Magento\Backend\Model\View\Result\Forward
	 */
	protected $resultRedirectFactory;
	
	/**
	 * @param Magento\Framework\App\Action\Context
	 * @param Magento\Backend\Model\View\Result\Redirect
	 * @param Magento\Framework\Controller\Result\ForwardFactory
	 * @param Magento\Framework\View\Result\PageFactory
	 */
	
	public function __construct(\Magento\Backend\App\Action\Context $context,
			\Magento\Backend\Model\View\Result\Redirect $resultRedirectFactory ,
			\Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
			\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{
		$this->resultRedirectFactory = $resultRedirectFactory;
		$this->resultForwardFactory= $resultForwardFactory;
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}
	public function execute()
    {
    	$resultRedirect = $this->resultRedirectFactory->create();
		
		$data=$this->getRequest()->getPost();
		if (isset($data['booking_location']['region_id']) && $data['booking_location']['region_id']!='') {
			$state = $data['booking_location']['region_id'];
		} else {
			$state = $data['booking_location']['region'];
		}
		if ($state == '')
		{
			$this->messageManager->addError('State/Province can\'t be empty');
			return $this->_redirect('booking/location/index');
    		
		}
    	if(!isset($data['booking_location']['id'])) {
    		$model=$this->_objectManager->create('Ced\Booking\Model\Location');
    	}else{
    		$model=$this->_objectManager->create('Ced\Booking\Model\Location')->load($data['booking_location']['id']);
    	}	
    	$model->setData('email',$data['booking_location']['email'])
    		  ->setData('contact',$data['booking_location']['contact'])
    		  ->setData('street_address',$data['booking_location']['street_address'])
    		  ->setData('city',$data['booking_location']['city'])
    		  ->setData('state',$state)
    		  ->setData('zip',$data['booking_location']['zip'])
    		  ->setData('country',$data['booking_location']['country'])
    		  ->save();
    	
    	$resultRedirect = $this->resultRedirectFactory->create();
    	$this->_redirect('booking/location/index');
    	$this->messageManager->addSuccess('You have successfully saved Location.');

	if ($this->getRequest()->getParam('back'))
	{
	   	return  $resultRedirect->setPath('booking/location/index', ['id' => $data['booking_location']['id'], '_current' => true]);
	}else{
		$this->_redirect('booking/location/index');
	}
	  
	}
}

