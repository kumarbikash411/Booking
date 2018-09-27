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

class Facilities extends \Magento\Backend\Block\Widget\Tab 
{
	public $setup;
	public $_bookingFactory;
	public $_countryFactory;
	public $_template = 'Ced_Booking::catalog/product/facilities.phtml';
	public $coreRegistry;
    public function __construct(
    		ModuleDataSetupInterface $setup,
       \Magento\Backend\Block\Template\Context $context, array $data = []
    ) {
    	$this->setup = $setup;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        parent::__construct($context, $data);
	
    }
 
    public function getRoomtypes()
    {
    	
    	$roomtype=$this->_objectManager->create ( 'Ced\Booking\Model\Roomtype')->getCollection();
    	return $roomtype;
    }

}
