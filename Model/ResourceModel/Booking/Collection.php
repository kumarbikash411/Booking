<?php 

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * You can check the licence at this URL: http://cedcommerce.com/license-agreement.txt
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Blog
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt  
 */ 
 
namespace Ced\Booking\Model\ResourceModel\Booking;

use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\DB\Select;
use Magento\Store\Model\Store;


class Collection extends \Magento\Eav\Model\Entity\Collection\VersionControl\AbstractCollection
{
    /**
     * Name of collection model
     */
    const BOOKING_MODEL_NAME = 'Ced\Booking\Model\Booking';

    /**
     * @var \Magento\Framework\Object\Copy\Config
     */
    protected $_fieldsetConfig;

    /**
     * @var string
     */
    protected $_modelName;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory                  $entityFactory
     * @param \Psr\Log\LoggerInterface                                          $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface      $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                         $eventManager
     * @param \Magento\Eav\Model\Config                                         $eavConfig
     * @param \Magento\Framework\App\Resource                                   $resource
     * @param \Magento\Eav\Model\EntityFactory                                  $eavEntityFactory
     * @param \Magento\Eav\Model\ResourceModel\Helper                           $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory                     $universalFactory
     * @param \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot
     * @param \Magento\Framework\Object\Copy\Config                             $fieldsetConfig
     * @param \Zend_Db_Adapter_Abstract                                         $connection
     * @param string                                                            $modelName
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
/*     public function __construct(
    		\Magento\Framework\Data\Collection\EntityFactory $entityFactory,
    		\Psr\Log\LoggerInterface $logger,
    		\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
    		\Magento\Framework\Event\ManagerInterface $eventManager,
    		\Magento\Eav\Model\Config $eavConfig,
    		\Magento\Framework\App\ResourceConnection $resource,
    		\Magento\Eav\Model\EntityFactory $eavEntityFactory,
    		\Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
    		\Magento\Framework\Validator\UniversalFactory $universalFactory,
    		\Magento\Store\Model\StoreManagerInterface $storeManager,
    		\Magento\Framework\Module\Manager $moduleManager,
    		\Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
    		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    		\Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
    		\Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
    		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
    		\Magento\Customer\Model\Session $customerSession,
    		\Magento\Framework\Stdlib\DateTime $dateTime,
    		GroupManagementInterface $groupManagement,
    		\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
    		$modelName = self::BOOKING_MODEL_NAME
    ) {
    	$this->moduleManager = $moduleManager;
    	$this->_catalogProductFlatState = $catalogProductFlatState;
    	$this->_scopeConfig = $scopeConfig;
    	$this->_productOptionFactory = $productOptionFactory;
    	$this->_catalogUrl = $catalogUrl;
    	$this->_localeDate = $localeDate;
    	$this->_customerSession = $customerSession;
    	$this->_resourceHelper = $resourceHelper;
    	$this->dateTime = $dateTime;
    	$this->_groupManagement = $groupManagement;
    	$this->_modelName = $modelName;
    	parent::__construct(
    			$entityFactory,
    			$logger,
    			$fetchStrategy,
    			$eventManager,
    			$eavConfig,
    			$resource,
    			$eavEntityFactory,
    			$resourceHelper,
    			$universalFactory,
    			$storeManager,
    			$connection
    	);
    } */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot,
        \Magento\Framework\DataObject\Copy\Config $fieldsetConfig,
        $connection = null,
        $modelName = self::BOOKING_MODEL_NAME
    ) {

        $this->_fieldsetConfig = $fieldsetConfig;
        $this->_modelName = $modelName;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $entitySnapshot,
            $connection
        );
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init($this->_modelName, 'Ced\Booking\Model\ResourceModel\Booking');
    }


    /**
     * Get SQL for get record count
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();
        $select->resetJoinLeft();

        return $select;
    }

    /**
     * Reset left join
     *
     * @param  int $limit
     * @param  int $offset
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected function _getAllIdsSelect($limit = null, $offset = null)
    {
        $idsSelect = parent::_getAllIdsSelect($limit, $offset);
        $idsSelect->resetJoinLeft();
        return $idsSelect;
    }
    /**
     * Retrieve Option values array
     *
     * @return array
     */
    public function toOptionArray($vendor_id = 0)
    {
    	$options = array();
    	$vendors = $this->addAttributeToSelect(array('city','email'));
    	if($vendor_id) {
    		$vendors->addAttributeToFilter('entity_id', array('eq'=>(int)$vendor_id));
    	}
    	$options['']=__('-- please select vendor --');
    	foreach($vendors as $vendor) {
    		$options[$vendor->getId()] = $vendor->getName().' ('.$vendor->getEmail().')';
    	}
    	return $options;
    }
    
  
}
