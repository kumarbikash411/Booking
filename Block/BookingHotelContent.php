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

namespace Ced\Booking\Block;

use Magento\Framework\View\Element\Template;
use Ced\Booking\Model\ProductFacilityRelationFactory;
use Ced\Booking\Model\RoomsFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data;
use Ced\Booking\Model\BookingCalendarsFactory;

/**
 * 
 * @author cedcoss
 *
 */
class BookingHotelContent extends Template
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
        ProductFacilityRelationFactory $ProductFacilityRelation,
        RoomsFactory $RoomsFactory,
        Timezone $timezone,
        Data $price,
        BookingCalendarsFactory $bookingCalendars,
    	  array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_productFacilityRelation = $ProductFacilityRelation;
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_RoomsFactory = $RoomsFactory;
        $this->_timeZone = $timezone;
        $this->price = $price;  
        $this->scopeConfig = $context->getScopeConfig();
        $this->bookingCalendars = $bookingCalendars;  
        $this->setCollection($this->getRoomsData());   
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if ($this->getCollection()) {
            // create pager block for collection 
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'booking.grid.record.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(5)->setCollection(
                $this->getCollection() // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }

        return $this;
    }
  
    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    } 
    
    public function getCurrentSymbol()
    {
        return $this->price;
    }

 
    public function getProductId()
    {
      if ($this->getRequest()->getParam('product_id')) {
         $id = $this->getRequest()->getParam('product_id');
      } elseif ($this->getRequest()->getParam('p')) {
        $id = '';
      } else {
         $id = $this->_coreRegistry->registry('current_product')->getId();
      }
      return $id;
    }

    public function getCurrentProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getParams()
    {
        $param = $this->getRequest()->getParams();
        return $param;
    }

    /* join all room table data */
    public function getRoomsData()
    {
        $param = $this->getParams();
        $collection = $this->_objectManager->get('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('main_table.product_id',$this->getProductId());
        $collection->getSelect()->joinLeft(
                ['image'=>'ced_booking_room_image_relation'],
                'main_table.id = image.room_id',
                array('image.image_name')
        )->group('main_table.id');
        $collection->addFieldToFilter('status',1);
        // if (isset($param['category']) && $param['category']!='') {
        //     $collection->addFieldToFilter('main_table.room_category_id',$param['category']);
        // }

        return $collection; 
    }
     /**
     * @return mixed
     *
     */
    public function getFacilitiesRelation()
    {
        $model =  $this->_productFacilityRelation->create();
        $bookingfacilities = $model->getCollection()->addFieldToFilter('product_id',$this->getProductId());
        $facilityids = $bookingfacilities->getColumnValues('facility_id');

        if (count($facilityids) !=0) {
            $facilitiesCollection = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
            $facilitiesCollection->addFieldToFilter('id', $facilityids);
            return $facilitiesCollection;
        } else {
            return null;
        }
    }
}
