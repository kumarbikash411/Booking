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
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Controller\Adminhtml\Location;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\GroupFactory;

class Index extends Action
{
     const ENTITY_TYPE = \Magento\Catalog\Model\Product::ENTITY;
    /**
     * @var Builder
     */
    protected $productBuilder;

    /**
     * @param Context $context
     * @param Builder $productBuilder
     */
    public function __construct(Context $context, Builder $productBuilder,\Magento\Framework\View\Result\PageFactory $resultPageFactory,
         EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup,
        GroupFactory $groupFactory)
    {
        parent::__construct($context);
        $this->productBuilder = $productBuilder;
        $this->_resultPageFactory = $resultPageFactory;
        $this->setup = $setup;
        $this->eavSetupFactory = $eavSetupFactory;
         $this->groupFactory = $groupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {   
            $resultPage = $this->_resultPageFactory->create();

            $resultPage->setActiveMenu('Ced_Booking::booking_location');

            $resultPage->getConfig()->getTitle()->prepend(__('Booking Location'));

            return $resultPage;

        
    }

    protected function _isAllowed()

    {
        return $this->_authorization->isAllowed('Ced_Booking::booking_location');

    }
}
