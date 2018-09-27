<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ced\Booking\Ui\DataProvider\Rooms\Facility\Image;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Renderer extends \Magento\Ui\Component\Listing\Columns\Column
{
    // const NAME = 'thumbnail';

    // const ALT_FIELD = 'name';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {

                if(empty($item['image']))
                {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                     
                    $path=$objectManager->get('Magento\Store\Model\StoreManagerInterface')
                    ->getStore()
                    ->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg' ;
                    $item[$fieldName . '_src'] = $path;
                        
                } else {
                
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $image=$objectManager->create('\Magento\Store\Model\StoreManagerInterface')

                            ->getStore()

                            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$item['image'];
                    $icon = $item['image'];
                
                    if ((strpos ( $icon, 'fa-') !== false) || (strpos ($icon, 'im-') !== false)) {
                        $icon_class = explode('-', $item['image'] ); 
                        $type_font = $icon_class[0];
                        $item[$fieldName . '_src'] = $type_font; 
                        $item[$fieldName . '_src'] = $item['image']; 
                        //'<i class="ace-icon '.$type_font.' '.$item['facility_image'].' size-icon"></i>';
                       
                    } else {
                        // $html = '<img src="'.$image . '"height="' . '50px' . '"';
                        // $html .= ' width="50px"height="50px" style="display:inline-block;margin:2px"/>';
                        $item[$fieldName . '_src'] = $image;
                    }
                    
                }

                // var_dump($html);


                //$product = new \Magento\Framework\DataObject($item);
               //  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
               //  $image=$objectManager->create('\Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$item['facility_image'];
               //  //$imageHelper = $this->imageHelper->init($product, 'product_listing_thumbnail');
               //  $item[$fieldName . '_src'] = $image;
               // // $item[$fieldName . '_alt'] = $this->getAlt($item) ?: $imageHelper->getLabel();
               //  // $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
               //  //     'catalog/product/edit',
               //  //     ['id' => $product->getEntityId(), 'store' => $this->context->getRequestParam('store')]
               //  // );
               //   $origImageHelper = $this->imageHelper->init($product, 'product_listing_thumbnail_preview');
               //   $item[$fieldName . '_orig_src'] = $origImageHelper->getUrl();
            }
        } //die;

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    // protected function getAlt($row)
    // {
    //     $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
    //     return isset($row[$altField]) ? $row[$altField] : null;
    // }
}
