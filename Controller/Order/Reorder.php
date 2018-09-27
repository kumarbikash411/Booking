<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ced\Booking\Controller\Order;

use Magento\Sales\Controller\OrderInterface;
use Magento\Sales\Controller\AbstractController\OrderLoaderInterface;
use Magento\Framework\App\Action;
use Magento\Framework\Registry;

class Reorder extends \Magento\Sales\Controller\Order\Reorder
{

	/**
     * @var \Magento\Sales\Controller\AbstractController\OrderLoaderInterface
     */
    protected $orderLoader;

    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @param Action\Context $context
     * @param OrderLoaderInterface $orderLoader
     * @param Registry $registry
     */
    public function __construct(
        Action\Context $context,
        OrderLoaderInterface $orderLoader,
        Registry $registry
    ) {
        $this->orderLoader = $orderLoader;
        $this->_coreRegistry = $registry;
        parent::__construct($context,$orderLoader,$registry);
    }

	public function execute()
	{
		$result = $this->orderLoader->load($this->_request);
        if ($result instanceof \Magento\Framework\Controller\ResultInterface) {
            return $result;
        }
        $order = $this->_coreRegistry->registry('current_order');
        $product = $order->getAllItems();
        foreach ($product as $value) {
            $productType[] = $value->getProductType();
        }
        if (in_array('booking',$productType)) {

            $this->messageManager->addError('You can not reorder product.You need to add product to cart manually');
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account');

        } else {
            // print_r($product->getTypeId()); die;
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            /* @var $cart \Magento\Checkout\Model\Cart */
            $cart = $this->_objectManager->get('Magento\Checkout\Model\Cart');
            $items = $order->getItemsCollection();
            foreach ($items as $item) {
                try {
                    $cart->addOrderItem($item);
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    if ($this->_objectManager->get('Magento\Checkout\Model\Session')->getUseNotice(true)) {
                        $this->messageManager->addNotice($e->getMessage());
                    } else {
                        $this->messageManager->addError($e->getMessage());
                    }
                    return $resultRedirect->setPath('*/*/history');
                } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
                    return $resultRedirect->setPath('checkout/cart');
                }
            }

            $cart->save();
            return $resultRedirect->setPath('checkout/cart');
        }
       
	}
}
