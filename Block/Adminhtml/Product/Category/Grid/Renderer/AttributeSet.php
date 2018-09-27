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

 * @author      CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */

namespace Ced\Booking\Block\Adminhtml\Product\Category\Grid\Renderer;

class AttributeSet extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

	protected $_storeManager;

	public function render(\Magento\Framework\DataObject $row)

	{   
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$attributeSetName = $objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($row->getAttributeSetId())->getAttributeSetName();
		return $attributeSetName;
	}
}
