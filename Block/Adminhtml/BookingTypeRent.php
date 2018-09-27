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
class BookingTypeRent extends \Magento\Backend\Block\Template
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
	public function getBookingtype()
	{
        $booking_type = $this->getRequest()->getParam('booking_type');
        return $booking_type;
	}
	public function getBookingtypeentfacilities()
	{
		$facilities = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection()-> addFieldToFilter('facility_booking_type',$this->getBookingtype());
		return $facilities;
	}
	public function getProductfacilityrelation()
	{
		$sku = $this->getSku();
		if (isset($sku)) {
		    $savedFacilties = $this->_objectManager->create('Ced\Booking\Model\ResourceModel\ProductFacilityRelation\Collection')->addFieldToFilter('sku',$this->getSku());
		   
		}
		foreach ($savedFacilties as $key => $value) {
			$ids = json_decode($value->getFacilityId());
		}
		if (isset($ids)) {
			return $ids;
		} else {
			return false;
		}
		
	}
}