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

namespace Ced\Booking\Controller\Adminhtml\CreateCategoryForRooms;

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
		$id=$this->getRequest()->getPost('id');
		$data=$this->getRequest()->getPost();

		$rcategoryCollection = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->getCollection()->addFieldToFilter('title',$data['title']);
		if (count($rcategoryCollection) != 0)
		{
			$this->messageManager->addError('Room category already exsist.');
			return $this->_redirect('booking/roomtype/roomcategorygrid');
		}

    	if(empty($id)){
    		$model=$this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory');
    	}else{
    		$model=$this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($id);
    	}	
    	$model->setData('title',$data['title'])
    		  ->setData('description',$data['description'])
    		  ->save();
    	
    	$resultRedirect = $this->resultRedirectFactory->create();
    	$this->_redirect('booking/roomtype/roomcategorygrid');
    	$this->messageManager->addSuccess('You have successfully saved Category.');

	if ($this->getRequest()->getParam('back'))
	{
	   	return  $resultRedirect->setPath('*/*/createcategory', ['id' => $model['id'], '_current' => true]);
	}else{
		$this->_redirect('booking/roomtype/roomcategorygrid');
	}
	  
	}
}

