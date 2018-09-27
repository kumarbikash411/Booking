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



namespace Ced\Booking\Model\ResourceModel;



class BookingCalendars extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb

{

	protected function _construct()

	{

		//wk_grid_record is table and grid_record_id is primary key of this table

		$this->_init('ced_booking_calendars', 'calendar_id');

	}

	/**

	 * Check if page identifier exist for specific store

	 * return page id if page exists

	 *

	 * @param string $identifier

	 * @param int $storeId

	 * @return int

	 */

	

	/* public function checkIdentifier($identifier)

	{

		$select = $this->_getLoadByIdentifierSelect($identifier);

		return $this->getConnection()->fetchOne($select);

	} */

	

	/**

	 * Retrieve load select with filter by identifier, store and activity

	 *

	 * @param string $identifier

	 * @param int|array $store

	 * @param int $isActive

	 * @return \Magento\Framework\DB\Select

	 */

	/* protected function _getLoadByIdentifierSelect($identifier)

	{

		 $select = $this->getConnection()->select()->from(

            ['blog_category' => $this->getMainTable()]

        )->where(

				'blog_category.url_key = ?',

				$identifier

		);

		return $select;

	}

	

	/*  

	public function validate($object, $request)

	{

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$attributeSet = $objectManager->create('Ced\Booking\Model\Attribute')->getCollection()

						->addFieldToFilter('url_key',array('like'=>$request->getParam('url_key')));

						

		if($object->getId())				

			$attributeSet->addFieldToFilter('id',array('nin'=>$object->getId()));

						

		if(count($attributeSet)>0){

			return ['url_key' => 'Url key already exists '];

		}

		

		return true;

	}

	 */

	/*  */

}

