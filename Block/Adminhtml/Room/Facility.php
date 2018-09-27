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

namespace Ced\Booking\Block\Adminhtml\Room;

class Facility extends \Magento\Backend\Block\Widget\Container
{
	/**
	 * @var string
	 */
	protected $_template = 'catalog/product/room_facility.phtml';

	/**
	 * @param \Magento\Backend\Block\Widget\Context $context
	 * @param array $data
	 */
	public function __construct(
			\Magento\Backend\Block\Widget\Context $context,
			array $data = []
	) {

		parent::__construct($context, $data);
		$this->_getAddButtonOptions();
		
	}

	/**
	 * Prepare button and gridCreate Grid , edit/add grid row and installer in Magento2
	 *
	 * @return \Magento\Catalog\Block\Adminhtml\Product
	 */
	protected function _prepareLayout()
	{	
		$this->setChild(
				'grid123',
				$this->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Room\Facility\Grid', 'hotel1.room.facility')
		);
		return parent::_prepareLayout();
	}

	/**
	 *
	 *
	 * @return array
	 */
	public function _getAddButtonOptions()
	{
		$splitButtonOptions = [
		'label' => __('Product Facilities'),
		'class' => 'primary',
		'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
				];
		$this->buttonList->add('add', $splitButtonOptions);
		
	}

	protected function _getCreateUrl()
	{
		return $this->getUrl(
				'booking/*/new'
		); 
	}

	/**
	 * Render grid
	 *
	 * @return string
	 */
	public function getGridHtml()
	{
		return $this->getChildHtml('grid123');
	}
}