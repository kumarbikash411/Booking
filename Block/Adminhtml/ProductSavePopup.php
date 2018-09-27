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
 * @author   	CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Block\Adminhtml;

use Magento\Framework\View\Element\FormKey;
class ProductSavePopup extends \Magento\Backend\Block\Template
{
	 protected $_formKey;
	function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		FormKey $_formKey,
		\Magento\Framework\objectManagerInterface $_objectManager,
		array $data = []
	)
	{
		$this->_objectManager = $_objectManager;
		$this->_formkey = $_formKey;
		parent::__construct($context, $data);
	}
	
	public function getRooms()
    {
        $rooms=$this->_objectManager->create ( 'Ced\Booking\Model\Rooms')->getCollection();
        return $rooms;
    }
 
    // public function getRoomtypes()
    // {
    //     $roomtype=$this->_objectManager->create ( 'Ced\Booking\Model\RoomTypeCategory')->getCollection();
    //     return $roomtype;
    // }
    public function getChildRooms()
    {
    	$childroomtypes = $this->_objectManager->create ( 'Ced\Booking\Model\Roomtype')->getCollection();
        return $childroomtypes;

    }
    public function getFormkey()
	{
		return $this->_formkey->getFormKey();
	}
	public function getRoomsById($roomid)
	{
	   $rooms=$this->_objectManager->create ( 'Ced\Booking\Model\Rooms')->load($roomid);
       return $rooms;
	}

	
}