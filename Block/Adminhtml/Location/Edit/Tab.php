<?php
/**
 * Copyright � 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ced\Booking\Block\Adminhtml\Location\Edit;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;

class Tab extends \Magento\Backend\Block\Widget\Tab 
{
    public $setup;
    public $_bookingFactory;
    public $_countryFactory;
    public $_template = 'Ced_Booking::location.phtml';
    public $coreRegistry;
    public function __construct(
            ModuleDataSetupInterface $setup,
            \Magento\Backend\Block\Template\Context $context, 
            Product $catelogproductmodel,
            Registry $registry,
            array $data = []
    ) {

        $this->setup = $setup;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_catalogproduct = $catelogproductmodel;
        $this->registry = $registry;
        parent::__construct($context, $data);
    
    }

    public function getRegistryData()
    {
        $data = $this->registry->registry('ced_location_data');
        return $data;
    }
}
