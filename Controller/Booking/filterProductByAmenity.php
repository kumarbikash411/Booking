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

namespace Ced\Booking\Controller\Booking;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class FilterProductByAmenity extends \Magento\Framework\App\Action\Action
{
    /**
     * @param resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param resultRedirectFactory
     */
    
    protected $resultRedirectFactory;
    
    /**
     * @param resultForwardFactory
     */
    
    protected $resultForwardFactory;
    
    /**
     * @param resultRedirect
     */
    
    protected $resultRedirect;
    
    /**
     * @param Magento\Framework\App\Action\Context $context
     * @param Magento\Framework\View\Result\PageFactory
     * @param Magento\Backend\Model\View\Result\Redirect
     * @param Magento\Framework\Controller\Result\ForwardFactory
     */
    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\Redirect $resultRedirectFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
            JsonFactory $resultJson
        )
    {
      parent::__construct($context);
      $this->_objectManager = $context->getObjectManager();
      $this->resultPageFactory = $resultPageFactory;
      $this->resultForwardFactory= $resultForwardFactory;
        $this->_resultJsonFactory = $resultJson;
    }
    
    /**
     * @param execute
     */
    public function execute()
    {
        $id = $this->_request->getParam('amenity_id');
        var_dump($id); die;
        $resultPage = $this->resultPageFactory->create(); 
        $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\RentHourlyProductListing')
                         ->setData(['post'=>$post])
                         ->setTemplate('Ced_Booking::rent_hourly_product_listing.phtml')
                         ->toHtml();
        $resultJson =  $this->_resultJsonFactory->create();
        $response = ['template'=>$template];
        return $resultJson->setData($response);
    }
}
