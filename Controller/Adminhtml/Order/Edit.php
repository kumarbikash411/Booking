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

 * @Order    Ced

 * @package     Ced_Booking

 * @author   	CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */ 

namespace Ced\Booking\Controller\Adminhtml\Order;

 

use Magento\Backend\App\Action;

 

class Edit extends \Magento\Backend\App\Action

{

    /**

     * Core registry

     *

     * @var \Magento\Framework\Registry

     */

    protected $_coreRegistry = null;

 

    /**

     * @var \Magento\Framework\View\Result\PageFactory

     */

    protected $resultPageFactory;

 

    /**

     * @param Action\Context $context

     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

     * @param \Magento\Framework\Registry $registry

     */

    public function __construct(

        Action\Context $context,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory,

        \Magento\Framework\Registry $registry

    ) {

        $this->resultPageFactory = $resultPageFactory;

        $this->_coreRegistry = $registry;

        parent::__construct($context);

    }

 

    /**

     * {@inheritdoc}

     */

    protected function _isAllowed()

    {

        return true;

    }

 

    /**

     * Init actions

     *

     * @return \Magento\Backend\Model\View\Result\Page

     */

    protected function _initAction()

    {	

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();

       

        $resultPage->setActiveMenu('Ced_Booking::booking')

            ->addBreadcrumb(__('Order'), __('Order'))

            ->addBreadcrumb(__('Manage Order'), __('Manage Order'));

       

        return $resultPage;

    }

 

    /**

     * Edit grid record

     *

     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect

     * @SuppressWarnings(PHPMD.NPathComplexity)

     */

	public function execute()

    {

        $id = $this->getRequest()->getParam('id');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

 		$model = $objectManager->create('Ced\Booking\Model\Order')->load($id);

 		$data=$model->getdata();

        $this->_coreRegistry->register('ced_order_data', $model);   

        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(

            $id ? __('Edit Post') : __('New Order'),

            $id ? __('Edit Post') : __('New Order')

        );  

        $resultPage->getConfig()->getTitle()->prepend(__('Grids'));

        $resultPage->getConfig()->getTitle()

            ->prepend($model->getId() ? $model->getTitle() : __('New Order'));

        return $resultPage;

    }

}