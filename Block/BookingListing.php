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

use Magento\Catalog\Api\CategoryRepositoryInterface;


class BookingListing extends \Magento\Catalog\Block\Product\ListProduct
{  
    
   protected $_category;
   protected $_objectManager;


    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ced\Booking\Helper\Data $helperdata,
        \Magento\Framework\Pricing\Helper\Data $priceRender,
        array $data = []
    ) {
        $this->_helper = $helperdata;
        $this->_objectManager = $objectManager;
        $this->_priceRender = $priceRender;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    public function getCurrentPage(){
        return $this->getRequest()->getActionName();
    }

    public function getCurrencySymbol()
    {
        return $this->_priceRender;
    }    
        

    public function getFormActionUrl(){
        $currentAction = $this->getCurrentPage();
        return $this->getUrl('booking/bookings/'.$currentAction);
    }

    public function getCurrentCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }
    public function getProductPrices()
    {
        $productData = $this->_getProductCollection()->getData();
        $prices = array_column($productData, 'price');
        return $prices;

    }

    /* get hotel banner from config */

    public function getConfigBanner()
    {
        $currentAction = $this->getCurrentPage();
        $configvalue = '';
        if($currentAction == 'hotel'){
            $configvalue  = $this->_helper->getStoreConfigValue('booking/banner_setting/hotel_banner');
        }else if($currentAction == 'daily'){
             $configvalue  = $this->_helper->getStoreConfigValue('booking/banner_setting/daily_banner');
        }else if($currentAction == 'hourly'){
                $configvalue  = $this->_helper->getStoreConfigValue('booking/banner_setting/hourly_banner');
        }
        return $configvalue;
    }


    /**
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */

    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {


        $currentAction = $this->getCurrentPage();
        $attributeSetName = '';
        if($currentAction == 'hotel'){
           $attributeSetName = 'Hotel Booking';
        }else if($currentAction == 'daily'){
             $attributeSetName = 'Daily Rent Booking';
        }else if($currentAction == 'hourly'){
            $attributeSetName = 'Hourly Rent Booking';
        }
        

            $attributesetData = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($attributeSetName,'attribute_set_name');
            $attributesetId = $attributesetData->getAttributeSetId();



            /** Apply filters here */
            /*$collection = $productCollection->create()
                ->addAttributeToSelect('*')
                ->load();
            foreach ($collection as $data) {
                $data['product_url'] = $data->getProductUrl();
                $data['image'] = $data->getThumbnail();
                $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($data->getAttributeSetId())->getAttributeSetName();
                if($attribute_set_name == 'Hotel Booking')
                {
                    $array[] = $data->getData();
                }
            }*/


            $cedLayer = $this->getLayer();
            if ($this->getShowRootCategory()) {
                $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
            }

            if ($this->_coreRegistry->registry('product')) {
                $cedCategories = $this->_coreRegistry->registry('product')
                    ->getCategoryCollection()->setPage(1, 1)
                    ->load();
                if ($cedCategories->count()) {
                    $this->setCategoryId(current($cedCategories->getIterator()));
                }
            }
            $origCategory = null;
            if ($this->getCategoryId()) {
                try {
                    $cedCategory = $this->categoryRepository->get($this->getCategoryId());
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                    $cedCategory = null;
                }
                if ($cedCategory) {
                    $origCategory = $cedLayer->getCurrentCategory();
                    $cedLayer->setCurrentCategory($cedCategory);
                }
            }

           /* $vendorId=$this->_coreRegistry->registry('current_vendor')->getId();
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $collection = $objectManager->create('Ced\CsMarketplace\Model\Vproducts')
                ->getVendorProducts(\Ced\CsMarketplace\Model\Vproducts::APPROVED_STATUS,$vendorId,0);*/


          //  $collection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

            /*$collection = $collection->create()
                ->addAttributeToSelect('*')
                ->load();*/
            $producrIds = [];
            $cedProductcollection = $cedLayer->getProductCollection();
            $cedProductcollection->addAttributeToFilter('attribute_set_id',$attributesetId);
            if($address = $this->getRequest()->getParam('address')){

                $locationIds = $this->_objectManager->create('Ced\Booking\Model\Location')->getCollection()->addFieldToFilter('city',array('eq'=>$address))->getColumnValues('id');

                $cedProductcollection->addAttributeToFilter('address',array('in'=>$locationIds));
            }
            if($searchByPrice = $this->getRequest()->getParam('search_by_price') && $currentAction == 'hotel'){

                $filterPrice = str_replace('$', '', $this->getRequest()->getParam('search_by_price')); 
                $price = explode("-", $filterPrice);

                $roomsCollection = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('status',1)->addFieldToFilter('price',
                             array(array('from'=>$price[0],'to'=>$price[1])               
                    ));
                if (count($roomsCollection)!=0) {
                    $producrIds = array_unique($roomsCollection->getColumnValues('product_id'));
                }
                //if (count($producrIds)!=0) {

                    $cedProductcollection->addAttributeToFilter('entity_id',array('in'=>$producrIds));

                //}
                
               
            } elseif (($searchByPrice = $this->getRequest()->getParam('search_by_price')) && ($currentAction == 'daily' || $currentAction == 'hourly')) {

                // $price = explode("-", $this->getRequest()->getParam('search_by_price'));
                // if(isset($price[0]) && is_numeric($price[0]))
                //     $cedProductcollection->addAttributeToFilter('price', array('gt' => $price[0]));
                // if(isset($price[1]) && is_numeric($price[1]))
                //     $cedProductcollection->addAttributeToFilter('price', array('lt' => $price[1]));
                $filterPrice = str_replace('$', '', $this->getRequest()->getParam('search_by_price')); 
                $price = explode("-", $filterPrice);
                $cedProductcollection->addAttributeToFilter('price',
                             array(array('from'=>$price[0],'to'=>$price[1])               
                    ));

            }

            // if($searchByRooms = $this->getRequest()->getParam('search_by_rooms')){
            //     if(is_numeric($searchByRooms) && $searchByRooms>0)
            //     $cedProductcollection->addAttributeToFilter('room_count', array('gteq' => $searchByRooms));
            // }
            $params = $this->getRequest()->getParams();
            if(isset($params['check_in']) && $params['check_in']!='' && isset($params['check_out']) && $params['check_out']!=''){

                $selectedDates=[];
                $selectedDateFrom=mktime(1,0,0,substr($params['check_in'],5,2),substr($params['check_in'],8,2),substr($params['check_in'],0,4));
                $selectedDateTo=mktime(1,0,0,substr($params['check_out'],5,2),substr($params['check_out'],8,2),substr($params['check_out'],0,4));

                if ($selectedDateTo>=$selectedDateFrom)
                {
                  array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                  while ($selectedDateFrom<$selectedDateTo)
                  {
                    $selectedDateFrom+=86400;
                    array_push($selectedDates,date('Y-m-d',$selectedDateFrom));
                  }
                }
                $roomIds = [];
                foreach ($selectedDates as $dates) {

                    $roomExcludeDays = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->getCollection()->addFieldToFilter('day_start',['lteq'=>$dates])->addFieldToFilter('day_end',['gteq'=>$dates]);
                    
                    if (count($roomExcludeDays)!=0) {
                        foreach ($roomExcludeDays as $data) {
                            $roomIds[] = $data->getRoomId();
                        }
                    }  
                }
                if (count($roomIds)!=0) {
                    $uniqueRoomIds = array_unique($roomIds);
                    $roomsData = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('id',$uniqueRoomIds);
                    $pIds = $roomsData->getColumnValues('product_id');
                    foreach ($pIds as $id) {
                        $cedProductcollection->addAttributeToFilter('entity_id',['neq'=>$id]);
                    }
                }
                
            }


            /*$products=array();
            $statusarray=array();
            foreach($collection as $productData){
                array_push($products,$productData->getProductId());
            }
            $cedProductcollection = $cedLayer->getProductCollection()
                ->addAttributeToSelect($objectManager->get('Magento\Catalog\Model\Config')
                    ->getProductAttributes())
                ->addAttributeToFilter('entity_id',array('in'=>$products))
                ->addStoreFilter($objectManager->get('Ced\CsMarketplace\Block\Vshops\ListBlock\ListBlock')->getCurrentStoreId())
                ->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

            $cat_id = $this->getRequest()->getParam('cat-fil');
            if(isset($cat_id)) {
                $cedProductcollection->joinField(
                    'category_id', 'catalog_category_product', 'category_id',
                    'product_id = entity_id', null, 'left'
                )
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('category_id', array(
                        array('finset', array('in'=>explode(',', $cat_id)))
                    ));
            }*/

            $this->_productCollection = $cedProductcollection;
            $this->prepareSortableFieldsByCategory($cedLayer->getCurrentCategory());

            if ($origCategory) {
                $cedLayer->setCurrentCategory($origCategory);
            }
        }
        $this->_productCollection->getSize();
        return $this->_productCollection;
    }

    public function getRoomPrices()
    {
        $roomPrice = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->getColumnValues('price');
        return $roomPrice;
    }

    public function getProductPrice(\Magento\Catalog\Model\Product $product)
    {
        if ($this->getCurrentPage() == 'hotel') {
            $price = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('product_id',$product->getId())->getColumnValues('price');
            if (count($price)!=0) {
                return min($price);
            } else {
                return "0.00";
            }
        } else  {
            $priceRender = $this->getPriceRender();

            $price = '';
            if ($priceRender) {
                $price = $priceRender->render(
                    \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                    $product,
                    [
                        'include_container' => true,
                        'display_minimal_price' => true,
                        'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
                    ]
                );
            }

            return $price;
        } 
       
    }


    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
