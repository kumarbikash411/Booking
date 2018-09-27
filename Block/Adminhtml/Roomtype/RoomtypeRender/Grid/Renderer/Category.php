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

 * @author   CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */



namespace Ced\Booking\Block\Adminhtml\Roomtype\RoomtypeRender\Grid\Renderer;


class Category extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer

{
    /**
     * 
     * @var unknown
     */
	protected $_storeManager;


	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
       
    }
    
	/**
	 * (non-PHPdoc)
	 * @see \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer::render()
	 */
	public function render(\Magento\Framework\DataObject $row)
	{  
		$html = '';
		$roomTypeCollection = $this->_objectManager->create('Ced\Booking\Model\RoomCategoryRelation')->getCollection()->addFieldToFilter('room_type_id',$row->getId());
		$categoryIds = $roomTypeCollection->getColumnValues('room_category_id');

		if (count($categoryIds) != 0) {
			foreach ($categoryIds as $id) {
				$catModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($id);
				$name[] = $catModel->getTitle();
			}
			foreach ($name as $title) {
				$html = $title.','.$html;

			}
		} else {
			$html = '';
		}

		return $html;
	
	}
}
