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
namespace Ced\Booking\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper

{

    const  HOTEL_TOP_LINK_ENABLED = 'booking/booking_config/hotel_link_enabled';
    const  DAILY_TOP_LINK_ENABLED = 'booking/booking_config/daily_link_enabled';
    const  HOURLY_TOP_LINK_ENABLED = 'booking/booking_config/hourly_link_enabled';
    const  HOTEL_TOP_LINK_LABEL = 'booking/booking_config/hotel_link_title';
    const  DAILY_TOP_LINK_LABEL = 'booking/booking_config/daily_link_title';
    const  HOURLY_TOP_LINK_LABEL = 'booking/booking_config/hourly_link_title';
    const LOCATION_ATTRIBUTES = ['email', 'contact', 'address', 'city', 'zip', 'state', 'country'];

    const HOTEL_ATTRIBUTE_SET = 'Hotel Booking';
    const DAILY_ATTRIBUTE_SET = 'Daily Rent Booking';
    const HOURLY_ATTRIBUTE_SET = 'Hourly Rent Booking';

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */

    protected $_config;

    protected $_scopeConfigManager;
    protected $_storeManager;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\State $appState
     */

    public function __construct(

        \Magento\Framework\App\Helper\Context $context,

        \Magento\Backend\App\ConfigInterface $config
    )

    {

        parent::__construct($context);

        $this->_config = $config;

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->_moduleManager = $context->getModuleManager();

        $this->_storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');

        $this->_scopeConfigManager = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

    }


    /**
     * @param $path
     * @return mixed
     */

    public function getStoreConfigValue($path)

    {

        return $this->_config->getValue($path);

    }

    /**
     * Function for getting Config value of current store
     *
     * @param string $path,
     */
    public function getStoreConfig($path,$storeId=null)
    {
    
        $store=$this->_storeManager->getStore($storeId);
        return $this->_scopeConfigManager->getValue($path, 'store', $store->getCode());
    }

    public function getRoomCategories() 
    {
      $room_category = $this->_objectManager->get('Ced\Booking\Model\RoomTypeCategory')->getCollection();
      return $room_category;

    }

    public function getRoomTypes() {
      $room_type = $this->_objectManager->get('Ced\Booking\Model\Roomtype')->getCollection();
      return $room_type;

    }

    /* get Booking Facilities */
    public function getFacilities()
    {
        $facilities = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
        return $facilities;
    }


  public function getHotelAttributeSetId(){
      
      $collection = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->getCollection();
      $collection->addFieldToFilter('attribute_set_name', self::HOTEL_ATTRIBUTE_SET);
      if(count($collection)>0)
        return $collection->getFirstItem()->getId();

      //default attribute set id
      return false;

    }

  public function getDailyAttributeSetId(){
      $collection = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->getCollection();
      $collection->addFieldToFilter('attribute_set_name', self::DAILY_ATTRIBUTE_SET);
      if(count($collection)>0)
        return $collection->getFirstItem()->getId();

      //default attribute set id
      return false;
  }

  public function getHourlyAttributeSetId(){
      $collection = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->getCollection();
      $collection->addFieldToFilter('attribute_set_name', self::HOURLY_ATTRIBUTE_SET);
      if(count($collection)>0)
        return $collection->getFirstItem()->getId();

      //default attribute set id
      return false;
  }

  public function isModuleEnabled($moduleName)
  {
    return $this->_moduleManager->isEnabled($moduleName);
  }

}