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

 * @author   	CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */

namespace Ced\Booking\Controller\Adminhtml\Products;



use Magento\Backend\App\Action;



/**

 * massDelete Controller

 *

 * @author     CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 */

class MassDelete extends \Magento\Backend\App\Action

{

	

	/**

	 * @param execute

	 */

	

	public function execute()

	{

		$data = $this->getRequest()->getParams();

		if ($data)

		{

			$postData = $this->getRequest()->getPost();

			$deletedIds = $this->getRequest()->getParam('id');

			$productDeleted = 0;

			foreach($deletedIds as $id)

			{

				$catalogProductModel = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($id);	

				$catalogProductModel->delete();

				$productDeleted++;

			}

			$this->_redirect('booking/products/index');

			$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $productDeleted));

		}

	}

}  

