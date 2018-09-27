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
 * @package     Ced_booking
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */


namespace Ced\Booking\Block\Adminhtml\Room;

use Magento\Store\Model\ResourceModel\Store\Collection;

class RoomType extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Ced_Booking::roomtype/rooms.phtml';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param array $data
     */
public function __construct(

        \Magento\Backend\Block\Template\Context $context,

        \Magento\Framework\objectManagerInterface $_objectManager,

        array $data = []

    ) 

    {
    	parent::__construct($context,$data);

        $this->_objectManager = $_objectManager;
    }

    public function getRoomsType()
    {
        $roomtype = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->getCollection();
        return $roomtype;
    }

    
}
