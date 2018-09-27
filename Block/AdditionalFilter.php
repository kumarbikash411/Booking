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
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data;

/**
 * 
 * @author cedcoss
 *
 */
class AdditionalFilter extends Template
{  
   /**
    * 
    * @param \Magento\Framework\View\Element\Template\Context $context
    * @param \Magento\Framework\Registry $coreRegistry
    * @param ProductFacilityRelationFactory $ProductFacilityRelation
    * @param RoomsFactory $RoomsFactory
    * @param array $data
    */
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        Timezone $timezone,
        Data $price,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_timeZone = $timezone;
        $this->price = $price;  
        $this->_categoryFactory = $categoryFactory;
        $this->registry = $registry;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_productCollectionFactory = $productCollectionFactory;
    } 

    public function getAmenities()
    {
        $amenitiesCollection = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
        return $amenitiesCollection;
    }



    public function getAmenitiesHtml($amenities)
    {
        if(count($amenities)>0){
            $amenity_filter = $this->getRequest()->getParam('amenity_id');
            $amenity_fil = [];
            if(isset($amenity_filter))
                $amenity_fil = explode(',', $amenity_filter);
            $html = '<ul class="booking-left-amenity-filter">';
            foreach ($amenities as $value) {
                
                $html .= '<li>';
                $html .= '<input onclick="filterProducts(this)" type="checkbox" name="amenity-fil" data-uncheckurl="'.$this->getUncheckFilterUrl($value->getId()).'" value="'.$this->getCheckFilterUrl($value->getId()).'"';
                if(in_array($value->getId(), $amenity_fil))
                    $html .= 'checked="checked"';
                $html .= '>';
                $label = $value->getTitle().'('.$this->getProductCountByAmenity($value->getId()).')';
                $html .= '<label>'.$label.'</label>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
    }

    public function getStarRatingHtml()
    {

        $starArray = [ 
            ['value'=>'5', 'label'=>'5 stars'],

            ['value'=>'4', 'label'=>'4 stars'],

            ['value'=>'3', 'label'=>'3 stars'],

            ['value'=>'2', 'label'=>'2 stars'],

            ['value'=>'1', 'label'=>'1 star']

        ];
        $starhtml = '<ul class="booking-left-star-filter" style="width:100%">';
        foreach ($starArray as $starvalue) {

                $star_filter = $this->getRequest()->getParam('rating');
                $star_fil = [];
                if(isset($star_filter))
                    $star_fil = explode(',', $star_filter);
                
                $starhtml .= '<li>';
                $starhtml .= '<input onclick="filterProducts(this)" type="checkbox" name="star-fil" data-uncheckurl="'.$this->getStarUncheckFilterUrl($starvalue['value']).'" value="'.$this->getStarCheckFilterUrl($starvalue['value']).'"';
                if(in_array($starvalue['value'], $star_fil))
                    $starhtml .= 'checked="checked"';
                $starhtml .= '>';
                for ($i=0;$i<$starvalue['value'];$i++) {
                    $starhtml .= '<i aria-hidden="true" class="fa fa-star fa-lg" style="margin-left:3px"></i> ';
                }
                $starhtml .= '('.$this->getProductsCountByStarRating($starvalue['value']).')';
                $starhtml .= '</li>'; 
        }
        $starhtml .= '</ul>';
        return $starhtml;
    } 

    public function getPriceFilterHtml()
    {
        $priceArray = [ 
            ['value'=>'less_than_2000', 'label'=>'Less than 2000'],

            ['value'=>'2000_3999', 'label'=>'2000 to 3999'],

            ['value'=>'4000_7999', 'label'=>'4000 to 7999'],

            ['value'=>'8000_10999', 'label'=>'8000 to 10999'],

            ['value'=>'greather_than_11000', 'label'=>'Greather than 11000']

        ];
        
         $price_filter = $this->getRequest()->getParam('price');
                $price_fil = [];
                if(isset($price_filter))
                    $price_fil = explode(',', $price_filter);
        $pricehtml = '<ul class="booking-left-price-filter" style="width:100%">';
        foreach ($priceArray as $pricevalue) {

                $pricehtml .= '<li>';
                $pricehtml .= '<input onclick="filterProducts(this)" type="radio" name="pice-fil" data-uncheckurl="'.$this->getPriceUncheckUrl($pricevalue['value']).'" value="?price='.$pricevalue['value'].'"';
                if(in_array($pricevalue['value'], $price_fil))
                    $pricehtml .= 'checked="checked"';
                $pricehtml .= '>';
                $pricehtml .= $pricevalue['label'];
                $pricehtml .= '('.$this->getProductsCountByPrice($pricevalue['value']).')';
                $pricehtml .= '</li>'; 
        }
        $pricehtml .= '</ul>';
        return $pricehtml;
    }

    public function getProductCollectionByCategory()
    {
        $category = $this->registry->registry('current_category');
        if ($category) {
            $categoryId = $category->getId();
            $category = $this->_categoryFactory->create()->load($categoryId);
            $productCollection = $this->_productCollectionFactory->create();
            $productCollection->addAttributeToSelect('*');
            $productCollection->addCategoryFilter($category);
        }  else {
            $productCollection = $this->_productCollectionFactory->create();
        }
        return $productCollection;
    }
    public function getProductCountByAmenity($id)
    {
        $productIds = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('facility_id',$id)->getColumnValues('product_id');

        $productCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('entity_id',array('in'=>$productIds));
        
        return count($productCollection);
    }

    public function getProductsCountByStarRating($rating)
    {
        $Collection = $this->getProductCollectionByCategory()->addAttributeToFilter('star_rating',$rating);
        return count($Collection);
    }

    public function getProductsCountByPrice($price)
    {
        if ($price == 'less_than_2000') 
            $filterByPriceProductCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('price', ['lteq' => '2000']);
        if ($price == 'greather_than_11000') 
            $filterByPriceProductCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('price', ['gteq' => '11000']);
        if ($price == '2000_3999') 
            $filterByPriceProductCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('price', ['gteq' => '2000'])->addFieldToFilter('price', ['lteq' => '3999']);
        if ($price == '4000_7999') 
            $filterByPriceProductCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('price', ['gteq' => '4000'])->addFieldToFilter('price', ['lteq' => '7999']);
        if ($price == '8000_10999') 
            $filterByPriceProductCollection = $this->getProductCollectionByCategory()->addAttributeToFilter('price', ['gteq' => '8000'])->addFieldToFilter('price', ['lteq' => '10999']);
        return count($filterByPriceProductCollection);
    }

    public function getCheckFilterUrl($amenityId)
    {
        $urlParams = array('_current' => true, '_escape' => true, '_use_rewrite' => true);
        
        $amenity_filter = $this->getRequest()->getParam('amenity_id');
        if(isset($amenity_filter)){
            $amenity_fil = explode(',', $amenity_filter);
            if(!in_array($amenityId, $amenity_fil)){
                $urlParams['_query'] = array('amenity_id'=> $amenity_filter.','.$amenityId);
            }
        }
        else
            $urlParams['_query'] = array('amenity_id'=> $amenityId);
        
        return $this->getUrl('*/', $urlParams);
    }

    public function getUncheckFilterUrl($id)
    {
        $urlParams = array('_current' => true, '_escape' => true, '_use_rewrite' => true);
        
        $amenity_filter = $this->getRequest()->getParam('amenity_id');
        
        if(isset($amenity_filter)){
            $amenity_fil = explode(',', $amenity_filter);
            if(in_array($id, $amenity_fil)){
                $amenity_fil = $this->remove_array_item($amenity_fil, $id);
                if(!count($amenity_fil))
                    return trim($this->getBaseUrl(), '/').rtrim($this->getRequest()->getOriginalPathInfo(),'/');
                elseif(count($amenity_fil)>0)
                    $urlParams['_query'] = array('amenity_id'=> implode(',',$amenity_fil));
            }
        }
        return $this->getUrl('*/*/*', $urlParams);
    }

    public function getStarCheckFilterUrl($rating)
    {
        $urlParams = array('_current' => true, '_escape' => true, '_use_rewrite' => true);
        
        $star_filter = $this->getRequest()->getParam('rating');
        if(isset($star_filter)){
            $star_fil = explode(',', $star_filter);
            if(!in_array($rating, $star_fil)){
                $urlParams['_query'] = array('rating'=> $star_filter.','.$rating);
            }
        }
        else
            $urlParams['_query'] = array('rating'=> $rating);
        
        return $this->getUrl('*/', $urlParams);
    }

    public function getStarUncheckFilterUrl($id)
    {
        $urlParams = array('_current' => true, '_escape' => true, '_use_rewrite' => true);
        
        $star_filter = $this->getRequest()->getParam('rating');
        
        if(isset($star_filter)){
            $star_fil = explode(',', $star_filter);
            if(in_array($id, $star_fil)){
                $star_fil = $this->remove_array_item($star_fil, $id);
                if(!count($star_fil))
                    return trim($this->getBaseUrl(), '/').rtrim($this->getRequest()->getOriginalPathInfo(),'/');
                elseif(count($star_fil)>0)
                    $urlParams['_query'] = array('rating'=> implode(',',$star_fil));
            }
        }
        return $this->getUrl('*/*/*', $urlParams);
    }

    public function getPriceUncheckUrl($price)
    {
        $urlParams = array('_current' => true, '_escape' => true, '_use_rewrite' => true);
        
        $price_filter = $this->getRequest()->getParam('price');
        
        if(isset($price_filter)){
            $price_fil = explode(',', $price_filter);
            if(in_array($price, $price_fil)){
                $price_fil = $this->remove_array_item($price_fil, $price);
                if(!count($price_fil)) {
                    return trim($this->getBaseUrl(), '/').rtrim($this->getRequest()->getOriginalPathInfo(),'/');
                }
                elseif(count($price_fil)>0)
                    $urlParams['_query'] = array('price'=> implode(',',$price_fil));
            }
        }
        return $this->getUrl('*/*/*', $urlParams);
    }


    public function remove_array_item( $array, $item ) {
        $index = array_search($item, $array);
        if ( $index !== false ) {
            unset( $array[$index] );
        }
        return $array;
    }

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }
}
