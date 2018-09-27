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
 * @package   Ced_CsMarketplace
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt  
 */

namespace Ced\Booking\Model;
use Magento\Framework\Api\AttributeValueFactory;

class Booking extends \Ced\Booking\Model\AbstractModel
{
    

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
    		ResourceModel\Booking $resource = null,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        //\Magento\Customer\Model\Session $customerSession,
        // \Ced\Booking\Helper\Data $dataHelper,
        \Ced\Booking\Helper\Acl $aclHelper,
       
        ResourceModel\Booking\Collection $resourceCollection = null,
        \Magento\Framework\ObjectManagerInterface $objectInterface,
        \Magento\Catalog\Model\Product\Url $url,
        array $data = []
    ) {

        // $this->_dataHelper = $dataHelper;
        $this->_aclHelper = $aclHelper;
        parent::__construct(
             $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $objectInterface,
            $url,
            $data
        );
    }
    
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ced\Booking\Model\ResourceModel\Booking');
    }

    /**
     * Load vendor by customer id
     *
     * @params int $customerId
     * @return Ced_CsMarketplace_Model_Vendor
     */
    public function loadByCustomerId($customerId) 
    {
        return $this->loadByAttribute('customer_id', $customerId);
    }
    
    /**
     * Load vendor by vendor/customer email
     *
     * @params String $email
     * @return Ced_CsMarketplace_Model_Vendor
     */
    public function loadByEmail($email) 
    {
        return $this->loadByAttribute('email', $email);
    }
    
    /**
     * Set customer
     */
    public function setCustomer($customer) 
    {
        $this->_customer = $customer;
        return $this;
    }
    
    /**
     * Get customer
     */
    public function getCustomer() 
    {
        if(!$this->_customer && $this->getCustomerId()) {
            $this->_customer = $this->_objectManager->get('Magento\Customer\Model\Customer')->load($this->getCustomerId());
        }
        return $this->_customer;
    }
    
   
    public function getEntityTypeId() 
    {
        $entityTypeId = $this->getData('entity_type_id');
        if (!$entityTypeId) {
            $entityTypeId = $this->getEntityType()->getId();
            $this->setData('entity_type_id', $entityTypeId);
        }
        return $entityTypeId;
    }
    
   
    

}
