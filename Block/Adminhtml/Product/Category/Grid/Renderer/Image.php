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

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

	protected $_storeManager;

	public function render(\Magento\Framework\DataObject $row)

	{   
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		if(empty($row->getThumbnail()))
		{
			
			$path=$objectManager->get('Magento\Store\Model\StoreManagerInterface')
			->getStore()
			->getBaseUrl().'pub/static/adminhtml/Magento/backend/en_US/Magento_Catalog/images/product/placeholder/thumbnail.jpg' ;
			$html = '<img id="' . $this->getColumn()->getId() . '" src="'.$path . '"height="' . '50px' . '"';
			
			$html .= '/>';
			return $html;
				
		}
		else{
		
		$image=$objectManager->create('\Magento\Store\Model\StoreManagerInterface')

				->getStore()

				->getBaseUrl().'pub/media/catalog/product'.$row->getThumbnail();

		$html = '<img id="' . $this->getColumn()->getId() . '" src="'.$image . '"height="' . '50px' . '"';
		$html .= '/>';
		
		return $html;
		}
	}
}
