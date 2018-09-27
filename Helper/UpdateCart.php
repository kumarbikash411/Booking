<?php
 /**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Booking
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Helper;
use \Magento\Framework\Message\ManagerInterface ;

class UpdateCart
{

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    protected $quote;

    /**
     * Plugin constructor.
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $objectManager  
    ) {
        $this->quote = $checkoutSession->getQuote();
        $this->_messageManager = $messageManager;
        $this->_objectManager = $objectManager;
    }

    /**
     * @param \Magento\Checkout\Model\Cart $subject
     * @param $data
     * @return array
     */
    public function beforeupdateItems(\Magento\Checkout\Model\Cart $subject,$data)
    {

        $quote = $subject->getQuote();
        foreach($data as $key=>$value){
            $item = $quote->getItemById($key);
            $productData = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
            $typeId = $productData->getTypeId();
            if ($typeId == 'booking') {
                $data[$item->getId()]['qty'] = $item->getQty();
                $this->_messageManager->addNoticeMessage('You can not update quantity from cart.');
            }
        }
        return [$data];
    }
}