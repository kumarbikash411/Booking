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

namespace Ced\Booking\Block\Adminhtml\Product\Steps;


class RoomFacilities extends \Magento\Ui\Block\Component\StepsWizard\StepAbstract
{

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\objectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_objectManager = $objectManager;
    }

    public function getCaption()
    {
        return __('Room Facilities');
    }

    public function getRoomFacilities()
    {
        $facilitiesModel = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
        return $facilitiesModel;
    }

}