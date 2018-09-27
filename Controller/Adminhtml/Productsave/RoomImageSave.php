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
 * @author   	CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Controller\Adminhtml\Productsave;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\File\Uploader;

class RoomImageSave extends Action
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
		JsonFactory $resultJsonFactory,
		UploaderFactory $uploaderFactory
	)
	{
		parent::__construct($context);
		$this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
		$this->uploaderFactory = $uploaderFactory;
	}
	public function execute()
	{
        $status = false;
		$messageStatus = '';
		$resultJson = $this->_resultJsonFactory->create();
		$resultPage = $this->_resultPageFactory->create();
		$roomid = $this->_request->getPost('room_id');

		$roomimagedata = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\RoomsImageRelation\Collection')->addFieldToFilter('ced_room_id',$roomid);
        
       try{
       	    $mediaDirectory = $this->_objectManager->get ( '\Magento\Framework\Filesystem' )->getDirectoryRead ( \Magento\Framework\App\Filesystem\DirectoryList::MEDIA );
	
		    $path = $mediaDirectory->getAbsolutePath ( 'images' );
		
            $uploader = $this->_objectManager->create ( '\Magento\MediaStorage\Model\File\Uploader', array (
					'fileId' => 'room_image'
			) );
			
		    $uploader->setAllowedExtensions ( array (
					'jpeg',
					'jpg',
					'png'
			) );
			
		    $uploader->setAllowRenameFiles ( false );
			
		    $uploader->setFilesDispersion ( false );
			
		    $files = $uploader->validateFile ();
			
		    $extension = pathinfo ( $files ['name'], PATHINFO_EXTENSION );
			
		    $fileName = $files ['name'];

            $newFileName = $fileName.time().'.'.$extension;
        
		    $result = $uploader->save ( $path, $newFileName );
            
            foreach ($roomimagedata as $key => $value) {
            	$imageid = $value['room_image_id'];
            }
            if (isset($imageid)) {
            	$model = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation')->load($imageid);
            } else {
            	$model = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation');
            }
		    $model->setData('image',$result['file'])
		          ->setData('ced_room_id',$roomid);
		    $model->save();
		    $status = true;
		    $messageStatus = __('Image Uploaded Successfully');
	    } catch(\Exception $e){
		    $messageStatus = $e->getMessage();
	    }
        
        $resultPage = $this->_resultPageFactory->create();
		$htmlImages = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\RoomsImage')->setData(['room_id'=>$roomid])->setTemplate('Ced_Booking::catalog/product/rooms/rooms_image.phtml')->toHtml();
		$response = ['html_images'=> $htmlImages,'status'=>$status,'mesg'=>$messageStatus];
		return $resultJson->setData($response);
	}

	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ced_Booking::update_booking');
    }
}