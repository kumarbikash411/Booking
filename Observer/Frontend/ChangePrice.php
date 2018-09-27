<?php

namespace Ced\Booking\Observer\Frontend;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class ChangePrice implements ObserverInterface
{

    protected $customerSession;
    
    public function __construct(
                RequestInterface $request,
                \Magento\Customer\Model\Session $customerSession,
                \Magento\Checkout\Model\Cart $cart
            )
    {
        $this->_request = $request;
        $this->customerSession = $customerSession;
        $this->cart = $cart;

    }
    public function execute(EventObserver $observer)
    {
        $items = $this->cart->getItems();
        
        $params = $this->_request->getParams();

        $item = $observer->getEvent()->getData('quote_item');  

        $product = $observer->getEvent()->getProduct();
        
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        $flag = false;

            if (isset($params['total-price'])) {
                $price = $params['total-price'];
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price); 

            }  elseif (isset($params['total_price'])) {
                $price = $params['total_price'];
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);  
            }  
    }
   
}
