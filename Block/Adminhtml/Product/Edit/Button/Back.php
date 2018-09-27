<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ced\Booking\Block\Adminhtml\Product\Edit\Button;

/**
 * Class Back
 */
class Back extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $currentUrl = $urlInterface->getCurrentUrl();
        if (strrpos($currentUrl,'product_type/booking')!==false)
        {
            return [
                'label' => __('Back'),
                'on_click' => sprintf("location.href = '%s';", $this->getUrl('booking/products/index')),
                'class' => 'back',
                'sort_order' => 10
            ];
        } else {

            return [
                'label' => __('Back'),
                'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/')),
                'class' => 'back',
                'sort_order' => 10
            ];
        }
    }
}
