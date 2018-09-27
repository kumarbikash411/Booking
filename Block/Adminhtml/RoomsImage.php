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
 
class RoomsImage extends \Magento\Backend\Block\Template
{
	protected $_formKey;
	function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		\Magento\Framework\objectManagerInterface $_objectManager,
		array $data = []
	)
	{
		$this->_objectManager = $_objectManager;
		parent::__construct($context, $data);
	}
	public function getUploadedImage()
	{
		$roomid = $this->getRoomId();
		$roomimagedata = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('room_id',$roomid);
		return $roomimagedata;
	}
}
