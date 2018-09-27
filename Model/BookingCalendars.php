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

namespace Ced\Booking\Model;

class BookingCalendars extends \Magento\Framework\Model\AbstractModel 

{

	/**

	 *@var ROUTE_ACTION_NAME

	 */

	const ROUTE_ACTION_NAME = 'attribute';

	

	/**

	 *@var ROUTE_CONTROLLER_NAME

	 */

	

	const ROUTE_CONTROLLER_NAME = 'index';

	

	/**

	 *@var Magento\Framework\Registry

	 */

	

	protected $_coreRegistry;

	

	/**

	 *@var Magento\Framework\Model\Context

	 */

	

	protected $_context;

	

	/**

	 *@var Magento\Framework\UrlInterface

	 */

	

	protected $urlBuilder;

	

	/**

	 *@var Magento\Framework\ObjectManagerInterface

	 */

	

	protected $objectManager;

	

	/**

	 * @param Magento\Framework\ObjectManagerInterface

	 * @param Magento\Framework\Model\Context

	 * @param Magento\Framework\UrlInterface

	 * @param Magento\Framework\Registry

	 * @param Magento\Framework\Model\ResourceModel\AbstractResource

	 * @param Magento\Framework\Data\Collection\AbstractDb

	 */

	

	public function __construct(

		\Magento\Framework\Model\Context $context,

		\Magento\Framework\ObjectManagerInterface $objectManager,

		\Magento\Framework\UrlInterface  $urlBuilder,

        \Magento\Framework\Registry $registry,
			
	    \Magento\Framework\Stdlib\DateTime\Timezone $timezone,

        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,

        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null

		) 

	{

		$this->objectManager = $objectManager;

		$this->_context = $context;

		$this->urlBuilder = $urlBuilder;

		$this->resource = $resource;

		$this->resourceCollection = $resourceCollection;

		$this->_coreRegistry = $registry;
		
		$this->_timeZone = $timezone;

		parent::__construct($context,$registry,$resource,$resourceCollection);

	}

	

	/**

	 *@var construct

	 */

	protected function _construct()

	{

		$this->_init('Ced\Booking\Model\ResourceModel\BookingCalendars');

	}
	
	public function getBookingCalendars($arrayseletct = array('*'),$conditions = array(),$orderBy = 'calendar_startdate',$sortOrder = 'ASC',$limit = 0,$curPage = 1)
	{
		$collection = $this->getCollection();
		$collection->addFieldToSelect($arrayseletct);

		if(count($conditions))
		{
			foreach($conditions as $key => $condition)
			{
				$collection->addFieldToFilter($key,$condition);
			}
		}
		if($limit > 0)
		{
			$collection->setPageSize($limit);
		}
		$collection->setCurPage($curPage);
		$collection->setOrder($orderBy,$sortOrder);
		//print_r($collection->getData()); die;
		return $collection;
	}

	/* get currents calendars by product id */
	public function getBookingCurrentCalendarsById($bookingsku,$bookingId,$arrayseletct = array('*'),$conditions = array(),$orderBy = 'calendar_startdate',$sortOrder = 'ASC',$limit = 0,$curPage = 1)
	{
		//$currDate = $this->_date->gmtDate('Y-m-d');
		$intCurrentTime = $this->_timeZone->scopeTimeStamp();
		$currDate = date('Y-m-d',$intCurrentTime);
		$collection = $this->getBookingCalendars($arrayseletct,$conditions,$orderBy,$sortOrder,$limit,$curPage);
		

		if ($bookingId != 0) {

			$hotelcollection = $this->getCollection();

			$hotelcollection->addFieldToFilter('calendar_booking_id',$bookingId);

			return $hotelcollection;

		} else {

			$collection->addFieldToFilter('calendar_booking_id',$bookingId)
					   ->addFieldToFilter(
				array('calendar_enddate','calendar_default_value'),
				array(
						array('gteq'=>$currDate),
						array('eq'=>1)
				)
			)->addFieldToFilter('calander_booking_sku',$bookingsku);

		    return $collection;
		}
		
	}

}