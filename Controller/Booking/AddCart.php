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
 * @author   	CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Controller\Booking;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class AddCart extends \Magento\Framework\App\Action\Action
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

    /*public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\Redirect $resultRedirectFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        JsonFactory $resultJsonFactory,
        PriceHelper $priceHelper,
         \Magento\Checkout\Model\Cart $cart,
         \Magento\Catalog\Model\Product $productRepository
        )
    {
        parent::__construct($context);
        $this->_objectManager = $context->getObjectManager();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory= $resultForwardFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_priceHelper = $priceHelper;
        $this->_cart = $cart;
        $this->_productRepository = $productRepository;
    }*/
    protected $_productRepository;
    protected $_cart;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $productRepository, 
        \Magento\Checkout\Model\Cart $cart
    )
    {   parent::__construct($context);
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
    }

    
    /**
     * @param execute
     */
    public function execute()
    {
      $data =  $this->getRequest()->getParams();
        $additionalOptions[] = array(
                            'label' => __('Check In'),
                            'value' => $data['selected_check_in'],
                            );
        $additionalOptions[] = array(
                                'label' => __('Check Out'),
                                'value' => $data['selected_check_out'],
                            );
                            
        $additionalOptions[] = array(
                                    'label' => __('Total Days'),
                                    'value' => $data['selected_qty'],
                                );
                            
        $additionalOptions[] = array(
                                    'label' => __('Room Type'),
                                    'value' => $data['booking_room_type'],
                                ); 
        /*$carts = $this->_cart;
        foreach ($carts->getQuote()->getAllItems() as $item) {            
            $item->addOption(array(
                            'code' => 'additional_options',
                            'value' => serialize($additionalOptions)
                            ));   
        }*/
        $params = array(
            'product' => $data['product'],
            'qty' =>$data['selected_qty']

        );
        $_product = $this->_productRepository->load($data['product']);
        $this->_cart->addProduct($_product,$params);
        $this->_cart->save();
        
        $this->_redirect('');



    }
}
