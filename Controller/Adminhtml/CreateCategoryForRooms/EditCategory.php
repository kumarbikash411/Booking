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

 

class EditCategory extends \Magento\Backend\App\Action

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

       

        $resultPage->setActiveMenu('Ced_Booking::booking_roomtype')

            ->addBreadcrumb(__('Roomtype'), __('Roomtype'))

            ->addBreadcrumb(__('Manage Roomtype'), __('Manage Roomtype'));

       

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

 		$model = $objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($id);

 		$data=$model->getdata();

        $this->_coreRegistry->register('ced_room_category_data', $model);   
       
        $resultPage = $this->_initAction();

        $resultPage->getConfig()->getTitle()

            ->prepend($model->getId() ? $model->getTitle() : __('New Category'));

        return $resultPage;

    }

}