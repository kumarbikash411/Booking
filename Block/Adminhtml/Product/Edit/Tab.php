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
 * @license     http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Block\Adminhtml\Product\Edit;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;

class Tab extends \Magento\Backend\Block\Widget\Tab 
{
    public $setup;
    public $_bookingFactory;
    public $_countryFactory;
    public $_template = 'Ced_Booking::catalog/product/edit.phtml';
    public $coreRegistry;
    public function __construct(
            ModuleDataSetupInterface $setup,
            \Magento\Backend\Block\Template\Context $context, 
            Product $catelogproductmodel,
            Registry $registry,
            \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformation,
            array $data = []
    ) {

        $this->setup = $setup;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_catalogproduct = $catelogproductmodel;
        $this->registry = $registry;
        parent::__construct($context, $data);
        $this->countryInformation = $countryInformation;
    
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getCurrentAddress()
    {
        $addressId = $this->registry->registry('current_product')->getAddress();
        $locationData = $this->_objectManager->create('Ced\Booking\Model\Location')->load($addressId)->getData();
        foreach ($locationData as $key=> $location) {
            if ($key == 'country') {
                $country = $this->countryInformation->getCountryInfo($location);
                $countryName = $country->getFullNameLocale();
                $locationData[$key] = $countryName;
            }
        }
        return $locationData;
    }

    public function getAllAddress()
    {
        $allAddress = $this->_objectManager->create('Ced\Booking\Model\Location')->getCollection()->getData();
        return $allAddress;
    }
}
