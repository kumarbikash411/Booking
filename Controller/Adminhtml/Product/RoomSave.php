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
namespace Ced\Booking\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Magento\Backend\App\Action\Context;

/**
 * Class Wizard
 */
class RoomSave extends Action
{
    /**
     * @var Builder
     */
    protected $productBuilder;

    /**
     * @param Context $context
     * @param Builder $productBuilder
     */
    public function __construct(Context $context, Builder $productBuilder,\Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->productBuilder = $productBuilder;
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
    }
}
