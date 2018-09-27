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
 * @package   Ced_Booking
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt  
 */ 
 
namespace Ced\Booking\Model\ResourceModel;
 
class Products extends \Magento\Eav\Model\Entity\AbstractEntity
{
	protected $_objectManager;
    /**
     * @param \Magento\Eav\Model\Entity\Context                  $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Validator\Factory               $validatorFactory
     * @param \Magento\Framework\Stdlib\DateTime                 $dateTime
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param array                                              $data
     */
            
    public function __construct(
        \Magento\Eav\Model\Entity\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Validator\Factory $validatorFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
    	\Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->_scopeConfig = $scopeConfig;
        $this->_validatorFactory = $validatorFactory;
        $this->dateTime = $dateTime;
        $this->storeManager = $storeManager;
        $this->_objectManager=$objectManager;
        $this->setType('ced_booking_entity');
        $this->setConnection('ced_booking_entity_read', 'ced_booking_entity_write');
    }
        
    
    public function getMainTable()
    {
        return $this->getTable('ced_booking_entity');
    }
    
    
    
   
}
