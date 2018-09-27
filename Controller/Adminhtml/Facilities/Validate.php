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

class Validate extends \Magento\Backend\App\AbstractAction

{

	/**

	 * @var \Magento\Framework\Controller\Result\JsonFactory

	 */

	protected $resultJsonFactory;

	

	/**

	 * @var \Magento\Framework\View\LayoutFactory

	 */

	protected $layoutFactory;

	/**

	 * @var objectManager

	 */

	protected $productFactory;

	 /**

     * @var objectManager

     */

    protected $_objectManager;



	/**

	 * @var PageFactory

	 */

	protected $resultPageFactory;

	

	/**

	 * @var Magento\Backend\App\Action\Context

	 * @var Magento\Framework\Controller\ResultFactory

	 */

	public function __construct(

			\Magento\Backend\App\Action\Context $context,

			\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,

			\Magento\Framework\View\LayoutFactory $layoutFactory

	) {

		$this->resultJsonFactory = $resultJsonFactory;

		$this->layoutFactory = $layoutFactory;

		$this->_objectManager = $context->getObjectManager();

		$this->resultPageFactory = $context->getResultFactory();

		parent::__construct($context);

	}

	/**

     * Validate product

     *

     * @return \Magento\Framework\Controller\Result\Json

     * @SuppressWarnings(PHPMD.CyclomaticComplexity)

     * @SuppressWarnings(PHPMD.NPathComplexity)

     */

    public function execute()

    {


    }

}