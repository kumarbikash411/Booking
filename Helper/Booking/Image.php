<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Booking
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
 
namespace Ced\Booking\Helper\Booking;
 
class Image extends \Magento\Framework\App\Helper\AbstractHelper

{
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $StoreManager

    ) {
        parent::__construct($context);
        $this->_objectManager = $objectManager;
        $this->_imageFactory = $imageFactory;  
        $this->_filesystem = $filesystem;
        $this->_storeManager = $StoreManager; 
      
       
    }

    public function resize($image, $width = null, $height = null,$placeholder)
    {
        if ($image) {
            $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('booking/store/banner/').$image;
            $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/'.$width.'/').$image;    

            //create image factory...
            $imageResize = $this->_imageFactory->create();         
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(TRUE);         
            $imageResize->keepTransparency(TRUE);         
            $imageResize->keepFrame(FALSE);         
            $imageResize->keepAspectRatio(false);         
            $imageResize->resize($width,$height);  
            //destination folder                
            $destination = $imageResized ;    
            //save image      
            $imageResize->save($destination);         

            $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$width.'/'.$image;
        } else {
            $absolutePath = $placeholder;
            $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/'.$width.'/').'image.jpg';   
            //create image factory...
            $imageResize = $this->_imageFactory->create();         
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(TRUE);         
            $imageResize->keepTransparency(TRUE);         
            $imageResize->keepFrame(FALSE);         
            $imageResize->keepAspectRatio(false);         
            $imageResize->resize($width,$height);  
            //destination folder                
            $destination = $imageResized ;    
            //save image      
            $imageResize->save($destination);         

            $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'resized/'.$width.'/'.'image.jpg'; 
        }

        return $resizedURL;
    } 

}
