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

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
class ProductRoomSave extends \Magento\Backend\App\Action
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

	public function __construct
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
		$status = false;
		$flag = '';
		$messageStatus = '';
		$resultJson = $this->_resultJsonFactory->create();
		$resultPage = $this->_resultPageFactory->create();
		
		$params = $this->_request->getParams();

		$roomsModel = $this->_objectManager->create('Ced\Booking\Model\Rooms');
		$roomscollection = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\Rooms\Collection');
		$roomfacilityid = isset($params['room_facilities_id']) ? json_encode($params['room_facilities_id']):'';

		try {

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

		} catch (\Exception $e) {
			var_dump($e->getMessage());
			die;
		}
 
		$image = isset($result['name']) ? $result['name'] : '';

		if(isset($params['room_id']))
		{	
			$roomsModel->load($params['room_id']);
				$roomsModel->setData('room_facilities_id',$roomfacilityid)
				->setData('room_max_adults',$params['room_max_adults'])
				->setData('room_max_children',$params['room_max_children'])
				->setData('room_product_sku',$params['room_product_sku'])
				->setData('room_status',$params['room_status'])
				->setData('room_type',$params['room_type'])
				->setData('child_room_type',$params['child_room_type'])
				->setData('room_desc',$params['room_desc'])
				->setData('room_images',$result['name']);
				$roomsModel->save();
				$messageStatus = __('You have saved Data');    
		} else {
			try {
				
				$data = $roomscollection->addFieldToFilter('room_product_sku',$params['room_product_sku'])->addFieldToFilter('room_type',$params['room_type'])->addFieldToFilter('child_room_type',$params['child_room_type'])->getData();
			
				if (!empty($data)){
					$messageStatus = __('Room Type already exist, Please select another room type');
					$flag = 0;

				} else {

				$roomsModel->setData('room_max_adults',$params['room_max_adults'])
							->setData('room_max_children',$params['room_max_children'])
							->setData('room_product_sku',$params['room_product_sku'])
							->setData('room_status',$params['room_status'])
							->setData('room_type',$params['room_type'])
							->setData('child_room_type',$params['child_room_type'])
							->setData('room_desc',$params['room_desc'])
							->setData('room_images',$result['name']);
					
				    $roomsModel->save();
				    $messageStatus = __('You have saved Data');    
				    $status = true; 
				    $flag = 1;
				} 
			} catch (\Exception $e) {

				$messageStatus = $e->getMessage();
				
				echo $messageStatus;
				
			}
		}
	
		$sku = $params['room_product_sku'];
		$passArray =['sku'=>$sku ,'newaddreq' => 0,'room_id'=>$roomsModel->getRoomId()];
		$template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\ProductSavePopup')
		->setData($passArray)->setTemplate('Ced_Booking::catalog/product/room_save_popup.phtml')
			->toHtml();
		
		$response = array('template'=> $template,'mesg'=>$messageStatus,'status'=>$status,'flag'=>$flag);
		return $resultJson->setData($response);
	}
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ced_Booking::update_booking');
    }
}