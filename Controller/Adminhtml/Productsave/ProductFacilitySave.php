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

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ProductFacilitySave extends \Magento\Backend\App\Action
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
    	$data=$this->getRequest()->getPost();
    	$model=$this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation');
  		$id  = $this->updateCheck($model,$data['sku']);
  		if($id)
  		{
  			$model=$this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->load($id);
  		}
  		$json = json_encode($data['facility_id']);
    	$model->setData('sku',$data['sku']);
    	$model->setData('facility_id', $json);
		$model->save();
		$resultJson = $this->_resultJsonFactory->create();
		$resultPage = $this->_resultPageFactory->create();
		
		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Product\Edit\Tab')
				   ->setData(['sku'=>$data['sku']])
		           ->setTemplate('Ced_Booking::catalog/product/edit.phtml')
			       ->toHtml();
	    $renttemplate = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\BookingTypeRent')
	               ->setData(['sku'=>$data['sku']])
		           ->setTemplate('Ced_Booking::catalog/product/bookingtype/booking_type_rent_facilities.phtml')
			       ->toHtml();

		$response = ['template'=> $template,'renttemplate'=>$renttemplate];
		return $resultJson->setData($response);
    	
	} 
	
	public function updateCheck($model,$sku)
	{
		foreach ($model->getCollection() as $value) {
			if($value->getSku() == $sku)
			{
				return $value->getProductFacilitiesId();
			}
		}
		return false;
	}	
}
