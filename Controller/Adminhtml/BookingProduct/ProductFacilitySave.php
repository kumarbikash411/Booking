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

namespace Ced\Booking\Controller\Adminhtml\BookingProduct;

use Magento\Backend\App\Action;

class ProductFacilitySave extends \Magento\Backend\App\Action
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
	 * @param Magento\Backend\App\Action\Context
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
    	if($id){
    		$data=$this->getRequest()->getPost();
    		$model=$this->_objectManager->create('Ced\Booking\Model\Facilities')->load($id);
    		$imagedel=$this->getRequest()->getPost('facility_image');
    		if(isset($imagedel['delete']))
    		if($imagedel['delete']==1)
    		{    
    			$mediaDirectory =$this->_objectManager->get('\Magento\Framework\Filesystem')
    			->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    			$path =$mediaDirectory->getAbsolutePath();
    			try{
    			  unlink($path.$model->getFacilityImage());
    			} catch(\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
    			}
    			$model->setData('facility_image','');
    			
    		}
    		
    	}else{
    		$data=$this->getRequest()->getPost();
    		$model=$this->_objectManager->create('Ced\Booking\Model\Facilities');
    	}	 
    	
    		try
    		{
    			$mediaDirectory =$this->_objectManager->get('\Magento\Framework\Filesystem')
    			->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    			 
    			$path = $mediaDirectory->getAbsolutePath('ced/booking/facilities');
    			$uploader = $this->_objectManager->create('\Magento\MediaStorage\Model\File\Uploader', array('fileId' =>'facility_image'));
    			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
    			$uploader->setAllowRenameFiles(false);
    			$uploader->setFilesDispersion(false);
    			$extension = pathinfo($_FILES['facility_image']['name'], PATHINFO_EXTENSION);
    			$fileName = $_FILES['facility_image']['name'].time().'.'.$extension;
    			$flag = $uploader->save($path, $fileName);
    			$fileName ='ced/booking/facilities/'.$fileName;
    			$model->setData('facility_status',$data['status'])
    			->setData('facility_title',$data['title'])
    			->setData('facility_type',$data['facility_type'])
    			->setData('facility_image',$fileName)
    			->setData('facility_booking_type',$data['facility_booking_type'])
    			->setData('facility_booking_id',$data['facility_booking_id'])
    			->setData('facility_desc',$data['facility_desc'])
    			->save();
   
    			if ($this->getRequest()->getParam('back'))
    			{
    				return  $resultRedirect->setPath('*/*/edit', ['id' => $model['facility_id'], '_current' => true]);
    			}else{
    				$this->_redirect('booking/facilities/index');
    			}
    	
    		
    		}
    		catch (\Exception $e)
    		{
    			 $this->messageManager->addError($e->getMessage());
    		}
    		
    		if ($data['facility_icon'] !== ''){
    			$model->setData('facility_image',$data['facility_icon']);
    		}
    		$model->setData('facility_status',$data['status'])
    		->setData('facility_title',$data['title'])
      		->setData('facility_type',$data['facility_type'])
    		->setData('facility_booking_type',$data['facility_booking_type'])
    		->setData('facility_booking_id',$data['facility_booking_id'])
    		->setData('facility_desc',$data['facility_desc'])
    		->save();
    		if ($this->getRequest()->getParam('back'))
    		{
    			return  $resultRedirect->setPath('*/*/edit', ['id' => $model['facility_id'], '_current' => true]);
    		}else{
    			$this->_redirect('booking/facilities/index');
    		}
    		
			if ($this->getRequest()->getParam('back'))
				{
				   	return  $resultRedirect->setPath('*/*/edit', ['id' => $model['facility_id'], '_current' => true]);
				}else{
					$this->_redirect('booking/facilities/index');
				} 
	  
	}
}

