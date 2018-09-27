<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetTimePicker implements ObserverInterface
{
    /**
     * @param mixed $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
    	die("dvcvf");
        /** @var \Magento\Framework\Data\Form $form */
        $form = $observer->getEvent()->getForm();

        $attributes = $this->weeeTax->getWeeeAttributeCodes(true);
        foreach ($attributes as $code) {
            $weeeTax = $form->getElement($code);
            var_dump($code);
            if ($weeeTax) {
                $weeeTax->setRenderer($this->layout->createBlock('Magento\Weee\Block\Renderer\Weee\Tax'));
            }
        } die;

        return $this;
    }
}
