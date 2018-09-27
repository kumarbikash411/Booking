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
namespace Ced\Booking\Block;

use Magento\Framework\View\Element\Template;

abstract class AbstractBooking extends Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data=[]
    )
    {
        parent::__construct($context,$data);
        $this->_coreRegistry = $coreRegistry;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function getProductId()
    {
        $id = $this->getProduct()->getId();
        return $id;
    }

    public function getProduct()
    {
        $product = $this->_coreRegistry->registry('current_product');

        return $product;
    }
}