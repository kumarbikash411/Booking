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

namespace Ced\Booking\Controller\Adminhtml\Roomtype;



use Magento\Backend\App\Action;



class Delete extends \Magento\Backend\App\Action

{

	/**

	 * @var execute

	 */

	public function execute()

	{

		$id = $this->getRequest()->getParam('id');
	
		$model = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($id);
		$model->setId($id)->delete();
		$this->_redirect('booking/roomtype/index');
		$this->messageManager->addSuccess(__('Record has been deleted'));

	}

}