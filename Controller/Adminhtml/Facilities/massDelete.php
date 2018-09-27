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

namespace Ced\Booking\Controller\Adminhtml\facilities;



use Magento\Backend\App\Action;



/**

 * massDelete category Controller

 *

 * @author     CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 */

class massDelete extends \Magento\Backend\App\Action

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

			$id = $this->getRequest()->getParam('id');

			$productDeleted = 0;

			foreach($id as $val)

			{

				$model = $this->_objectManager->create('Ced\Booking\Model\Facilities')->load($val);	

				$model->setId($val)->delete();

				$productDeleted++;

			}

			$this->_redirect('booking/facilities/index');

			$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $productDeleted));

		}

	}

}  

