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

namespace Ced\Booking\Controller\Adminhtml\Facilities;

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
        $image = "";

        if($id){
            $model=$this->_objectManager->create('Ced\Booking\Model\Facilities')->load($id);
            $imagedel=$this->getRequest()->getPost('image');
            if(isset($imagedel['delete']))
            if($imagedel['delete']==1)
            {    
                $mediaDirectory =$this->_objectManager->get('\Magento\Framework\Filesystem')
                ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $path =$mediaDirectory->getAbsolutePath();
                try{
                  unlink($path.$model->getImage());
                } catch(\Exception $e) {
                     $this->messageManager->addError($e->getMessage());
                }
                $model->setData('image','');
                
            }
            
        }else{
            
            $model=$this->_objectManager->create('Ced\Booking\Model\Facilities');
        }


        if(isset($data['image_type']) && $data['image_type']=='file'){

            if (preg_match('/png/', $_FILES['image']['type']) || preg_match('/jpg/', $_FILES['image']['type']) || preg_match('/jpeg/', $_FILES['image']['type']) || preg_match('/gif/', $_FILES['image']['type'])) {

                if(isset($_FILES['image']['name'])) {
                        try {
                            $mediaDirectory = $this->_objectManager->get('\Magento\Framework\Filesystem')
                                ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                            $path = $mediaDirectory->getAbsolutePath('ced/booking/facilities');
                            $uploader = $this->_objectManager->create('\Magento\MediaStorage\Model\File\Uploader', array('fileId' => 'image'));

                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);

                            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                            $fileName = time() . '.' . $extension;

                            $uploader->save($path, $fileName);
                            $image = 'ced/booking/facilities/' . $fileName;
                            $model->setData('image', $image);

                        } catch (\Exception $e) {
                            $this->messageManager->addError($e->getMessage());
                        }
                }
            } elseif ($_FILES['image']['name'] !='') {
                $this->messageManager->addError('This file type is not supported');
                return $resultRedirect->setPath('booking/facilities/new'); 
            }
        }else if(isset($data['facility_icon'])){
            $image = $data['facility_icon'];
            $model->setData('image', $image);
        }

        $model->setData('status',$data['status'])
            ->setData('title',$data['title'])
            ->setData('type',$data['type'])
            ->setData('image_type',$data['image_type'])
            ->setData('description',$data['description'])
            ->save();

            $this->messageManager->addSuccess('You have successfully saved facility.');

            if ($this->getRequest()->getParam('back'))
            {
                return  $resultRedirect->setPath('*/*/edit', ['id' => $model['id'], '_current' => true]);
            }else{
                $this->_redirect('booking/facilities/index');
            }
      
    }
}

