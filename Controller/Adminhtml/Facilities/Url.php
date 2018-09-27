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

namespace Ced\Booking\Controller\Adminhtml\Facilities;



use Magento\Backend\App\Action\Context;

use Magento\Framework\View\Result\PageFactory;

use Magento\Backend\App\Action;



/**

 *  category profile  Controller

 *

 * @author     CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 */

class Url extends \Magento\Backend\App\AbstractAction

{

	/**

     * @var \Magento\Framework\View\Result\PageFactory

     */

    protected $resultPageFactory;

	

	/**

     *

     * @var \Magento\Framework\App\ObjectManager $objectManager

     */

    protected $_objectManager;

    

    /**

     * @param Magento\Framework\App\ObjectManager $objectManager

     * @param Magento\Framework\View\Result\PageFactory

     * @param Magento\Framework\View\Result\PageFactory

     */

	public function __construct(

		\Magento\Backend\App\Action\Context $context,

		\Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {



    	parent::__construct($context);

    	$this->resultPageFactory = $resultPageFactory;

    	$this->_objectManager =  $context->getObjectManager();

    }

	/**

	 * @param execute

	 */

	 public function execute()

	 {

	 	$url_key = $this->getRequest()->getParam('url_key');

	 	$collection = $this->_objectManager->create('Ced\Blog\Model\Blogcat')->getCollection()

	 				  ->addFieldToFilter('url',$url_key);
	 				  

	 
	 }

}