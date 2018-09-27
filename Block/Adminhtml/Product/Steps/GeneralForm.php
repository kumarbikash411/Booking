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


class GeneralForm extends \Magento\Ui\Block\Component\StepsWizard\StepAbstract
{

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $ObjectManager,
        \Magento\Framework\Registry $Registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_objectManager = $ObjectManager;
        $this->registry = $Registry;
    }

    public function getCaption()
    {
        return __('General Information');
    }

    public function getRoomsCollection()
    {
        $product = $this->registry->registry('current_product');
        $Pid = $product->getId()!='' ? $product->getId(): '';
        $collection = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('product_id',$Pid);

        if (count($collection) != 0) {
            foreach ($collection as $coll) {

                $categorymodel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($coll->getRoomCategoryId());
                $roomTypemodel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($coll->getRoomTypeId());

                $array[] = ['room_category'=> $categorymodel['title'],
                            'room_type' => $roomTypemodel['title']
                               ];
                }
                return json_encode($array);
            } else {
                return null;
            }
    }
}