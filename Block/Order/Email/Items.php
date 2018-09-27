<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Sales Order Email order items
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Ced\Booking\Block\Adminhtml\Order\Email;

class Items extends \Magento\Framework\View\Element\Template
{

    
    protected function _prepareItem(\Magento\Framework\View\Element\AbstractBlock $renderer)
    {
        return $this;
    }

    /**
     * Return product type for quote/order item
     *
     * @param \Magento\Framework\DataObject $item
     * @return string
     */
    protected function _getItemType(\Magento\Framework\DataObject $item)
    {
        if ($item->getOrderItem()) {
            $type = $item->getOrderItem()->getProductType();
        } elseif ($item instanceof \Magento\Quote\Model\Quote\Address\Item) {
            $type = $item->getQuoteItem()->getProductType();
        } else {
            $type = $item->getProductType();
        }
        return $type;
    }

    /**
     * Get item row html
     *
     * @param   \Magento\Framework\DataObject $item
     * @return  string
     */
    public function getItemHtml(\Magento\Framework\DataObject $item)
    {
        $type = $this->_getItemType($item);

        $block = $this->getItemRenderer($type)->setItem($item);
        $this->_prepareItem($block);
        return $block->toHtml();
    }
}
