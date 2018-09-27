<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Simple product data view
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Ced\Booking\Block\Product\View;

use Magento\Framework\Data\Collection;
use Magento\Framework\Json\EncoderInterface;
use Magento\Catalog\Helper\Image;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{
    /**
     * @var \Magento\Framework\Config\View
     */
    protected $configView;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context,$arrayUtils, $jsonEncoder, $data);
        $this->jsonEncoder = $jsonEncoder;
        $this->_objectManager = $objectManager;
        
    }

   
    /**
     * Retrieve product images in JSON format
     *
     * @return string
     */
    public function getGalleryImagesJson()
    {
        $imagesItems = [];
        $path = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
                          ->getStore()
                          ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'ced/booking/room/images/';
        $position=0;
        foreach ($this->getImages() as $image) {
            $position = $position+1;
            $imagesItems[] = [
                'thumb' => $path.$image->getImageName(),
                'img' =>   $path.$image->getImageName(),
                'full' =>  $path.$image->getImageName(),
                'caption' => '',
                'position' => $position,
                'isMain' => '',
            ];
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
                          ->getStore()
                          ->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg',
                'img' => $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
                          ->getStore()
                          ->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg',
                'full' => $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
                          ->getStore()
                          ->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg',
                'caption' => '',
                'position' => '0',
                'isMain' => true,
            ];
        }
        return json_encode($imagesItems);
    }

  
}
