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

namespace Ced\Booking\Controller\Adminhtml\CreateCategoryForRooms;



use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;



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
		$id = $this->getRequest()->getPostValue();
     	foreach ($id as $key=>$value) {
    		if($key=="selected"){
    			foreach($value as $ids){
    			    $aid=$value;
    				$roomModel = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($ids,'room_category_id')->delete();
    				$roomCatModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($ids);
    				$roomCatModel->delete();
    				
    			} 
    		}
    	}
     
    	$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',count($aid)));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('booking/roomtype/roomcategorygrid');

	}

}  

