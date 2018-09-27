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

namespace Ced\Booking\Controller\Booking\Checkout\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;

class Add extends \Magento\Checkout\Controller\Cart\Add
{ 
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
			$productRepository
        );
    }
    /**
	 * Add product to shopping cart 
	 *
	 * @return \Magento\Framework\Controller\Result\Redirect
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function execute()
	{
		if (!$this->_formKeyValidator->validate($this->getRequest())) {
			return $this->resultRedirectFactory->create()->setPath('*/*/');
		}

		$params = $this->getRequest()->getParams();
        $productId = $this->getRequest()->getParam('product');
        $currentProduct = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);             

        //check if not booking type product
        if ($currentProduct->getTypeId() != 'booking'){
            return parent::execute();    
        }

        //show booking product page
        if ($currentProduct->getTypeId() == 'booking' && !isset($params['booking-type'])) {
            return $this->goBack($currentProduct->getProductUrl());
        }

		try {

			if (isset($params['qty'])) {

				$filter = new \Zend_Filter_LocalizedToNormalized(
					['locale' => $this->_objectManager->get('Magento\Framework\Locale\ResolverInterface')->getLocale()]
				);
				$params['qty'] = $filter->filter($params['qty']);
			}

			$product = $this->_initProduct();
			$related = $this->getRequest()->getParam('related_product');
			/**
			 * Check product availability
			 */
			if (!$product) {
				
				return $this->goBack();
			}
			//var_dump($params['product']); die;
			/*start booking code */
			
			// if(isset($params['product']) && (int)$params['product'] > 0)
			// {
			// 	$productId = (int)$params['product'];
			// 	$this->cart->removeItem($productId);
			// }
            
            //$product->addCustomOption('additional_options', serialize($additionalOptions));

			$this->cart->addProduct($product, $params);

			if (!empty($related)) {

				$this->cart->addProductsByIds(explode(',', $related));
			}

			$this->cart->save();


			/**
			 * @todo remove wishlist observer \Magento\Wishlist\Observer\AddToCart
			 */
			$this->_eventManager->dispatch(
				'checkout_cart_add_product_complete',
				['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
			);

			if (!$this->_checkoutSession->getNoCartRedirect(true)) {
				if (!$this->cart->getQuote()->getHasError()) {
					$message = __(
						'You added %1 to your shopping cart.',
						$product->getName()
					);
					$this->messageManager->addSuccessMessage($message);
				}
				return $this->goBack(null, $product);
			}
		} catch (\Magento\Framework\Exception\LocalizedException $e) {
			if ($this->_checkoutSession->getUseNotice(true)) {
				$this->messageManager->addNotice(
					$this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($e->getMessage())
				);
			} else {
				$messages = array_unique(explode("\n", $e->getMessage()));
				foreach ($messages as $message) {
					$this->messageManager->addError(
						$this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($message)
					);
				}
			}

			$url = $this->_checkoutSession->getRedirectUrl(true);


			if (!$url) {
				$cartUrl = $this->_objectManager->get('Magento\Checkout\Helper\Cart')->getCartUrl();
				$url = $this->_redirect->getRedirectUrl($cartUrl);
			}

			return $this->goBack($url);

		} catch (\Exception $e) {

			$this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
			$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
			return $this->goBack();
		}
	}
}