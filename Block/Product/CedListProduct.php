<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ced\Booking\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Product list
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CedListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
   public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_objectManager = $objectManager;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    /**
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {

            $layer = $this->getLayer();
            /* @var $layer \Magento\Catalog\Model\Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if ($this->_coreRegistry->registry('product')) {
                // get collection of categories this product is associated with
                $categories = $this->_coreRegistry->registry('product')
                    ->getCategoryCollection()->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                try {
                    $category = $this->categoryRepository->get($this->getCategoryId());
                } catch (NoSuchEntityException $e) {
                    $category = null;
                }

                if ($category) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                }
            }

            $bookingProductCollection = $layer->getProductCollection();

            if ($amenityIds = $this->getRequest()->getParam('amenity_id')) {
                $amenityIdArray = explode(',', $amenityIds);
                $amenityRelationCollection = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('id',['in'=>$amenityIdArray]);
                $productIds = $amenityRelationCollection->getColumnValues('product_id');

                $bookingProductCollection->addAttributeToFilter('entity_id',['in'=>$productIds]);
            } 
            if ($starRating = $this->getRequest()->getParam('rating')) {
                $starRatingArray = explode(',', $starRating);
                $bookingProductCollection->addAttributeToFilter('star_rating',['in'=>$starRatingArray]);
            }
            if ($price = $this->getRequest()->getParam('price')) {

                    if ($price == 'less_than_2000') 
                        $bookingProductCollection->addAttributeToFilter('price', ['lteq' => '2000']);
                    if ($price == 'greather_than_11000') 
                        $bookingProductCollection->addAttributeToFilter('price', ['gteq' => '11000']);
                    if ($price == '2000_3999') 
                        $bookingProductCollection->addAttributeToFilter('price', ['gteq' => '2000'])->addAttributeToFilter('price', ['lteq' => '3999']);
                    if ($price == '4000_7999') 
                        $bookingProductCollection->addAttributeToFilter('price', ['gteq' => '4000'])->addAttributeToFilter('price', ['lteq' => '7999']);
                    if ($price == '8000_10999') 
                        $bookingProductCollection->addAttributeToFilter('price', ['gteq' => '8000'])->addAttributeToFilter('price', ['lteq' => '10999']);
                
            }
            $this->_productCollection = $bookingProductCollection;
            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
           
            
        }

        return $this->_productCollection;
    }
}
