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

namespace Ced\Booking\Controller\Adminhtml\Roomtype;

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
		$id=$this->getRequest()->getParam('id');
		$data=$this->getRequest()->getPost();

    	if($id==''){
    		$model=$this->_objectManager->create('Ced\Booking\Model\Roomtype');
    	}else{
    		$model=$this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($id);
    	}	
    	$model->setData('status',$data['status'])
    		->setData('title',$data['title'])
    		->setData('max_allowed_child',$data['max_allowed_child'])
    		->setData('min_allowed_child',$data['min_allowed_child'])
    		->save();

    	$roomTypeModel = $this->_objectManager->create('Ced\Booking\Model\Roomtype');

	    $roomTypeId = $roomTypeModel->load($data['title'],'title')->getId();
	    $roomCategoryRelationCollection = $this->_objectManager->create('Ced\Booking\Model\RoomCategoryRelation')->getCollection()->addFieldToFilter('room_type_id',$roomTypeId);

	   	if (count($roomCategoryRelationCollection)!=0) {
	   		foreach ($roomCategoryRelationCollection as $collection) {
	   			
	   			$RoomCategoryRelationModel = $this->_objectManager->create('Ced\Booking\Model\RoomCategoryRelation');
	    		$RoomCategoryRelationModel->load($roomTypeId,'room_type_id')->delete();
	   		}
	    			
	    }	

    	foreach ($data['category_id'] as $key=>$catId) {

	    			$RoomCategoryRelationModel = $this->_objectManager->create('Ced\Booking\Model\RoomCategoryRelation');

	    			$RoomCategoryRelationModel->setData('room_type_id',$roomTypeId)
	    								  	  ->setData('room_category_id',$catId);
	    			$RoomCategoryRelationModel->save();

    	} 
    	
    	$resultRedirect = $this->resultRedirectFactory->create();
    	$this->_redirect('booking/roomtype/index');
    	$this->messageManager->addSuccess('You have successfully saved room type.');
	if ($this->getRequest()->getParam('back'))
	{
	   	return $resultRedirect->setPath('*/*/edit', ['id' => $model['id'], '_current' => true]);
	}else{
		$this->_redirect('booking/roomtype/index');
	}
	  
	}
}

