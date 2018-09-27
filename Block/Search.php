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
use Ced\Booking\Model\BookingCalendarsFactory;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class Search extends Template
{  
	
   protected $_category;
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        BookingCalendarsFactory $BookingCalendars,
        Timezone $timezone,
        PriceHelper $priceHelper,
    	array $data=[]
    )
    {
        parent::__construct($context,$data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_objectManager = $objectManager;
        $this->bookingCalendars = $BookingCalendars;
        $this->_timeZone = $timezone;
        $this->_priceHelper = $priceHelper;
    }


    

	
    public function getsubcategory()
    {
    	$subcat=[];
    	$catdata = $this->_objectManager->create('Magento\Catalog\Model\Category')->getCollection()->AddAttributeToSelect('*');
    	foreach ($catdata as $value) {
			 
			if ($value ['entity_id'] !== '1' && $value ['entity_id'] !== '2') {
				$subcat [] = $value->getData ();
			}
		}
    	 return $subcat;
    }

    public function getBookingList()
    {
    	/** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
    	$productCollection = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
    	
    	/** Apply filters here */
    	$collection = $productCollection->create()
    	->addAttributeToSelect('*')
    	->load();
       /*	foreach ($collection as $key=>$value) {
    		$value->getThumbnail();
    		$value->getId();
    		
    	}*/
    	return $collection;
    }

    public function getPriceHelper()
    {
        return $this->_priceHelper;
    }

    public function getcurrentdate()
    {
       $intCurrentTime = $this->_timeZone->scopeTimeStamp();
       $currDate = date('Y-m-d',$intCurrentTime);
       return $currDate;
    }

    public function getCalendarData()
    {
        $model = $this->bookingCalendars->create();
        $data = $model->getCollection();
        foreach ($data as $value) {
            if (($this->getcurrentdate()>=$value->getCalendarStartdate() && $this->getcurrentdate()<=$value->getCalendarEnddate())) {

                $price[] = $value->getCalendarPrice();
            }
        }
        $maxprice = max($price);
        return $maxprice;
    }
    
}
