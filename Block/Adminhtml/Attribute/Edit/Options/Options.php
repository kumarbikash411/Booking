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

namespace Ced\Booking\Block\Adminhtml\Attribute\Edit\Options;

use Magento\Store\Model\ResourceModel\Store\Collection;

class Options extends \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $_attrOptionCollectionFactory;

    /**
     * @var string
     */
    protected $_template = 'Ced_Booking::Options/options.phtml';

    /**
     * @var \Magento\Framework\Validator\UniversalFactory $universalFactory
     */
    protected $_universalFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param array $data
     */
public function __construct(

        \Magento\Backend\Block\Template\Context $context,

        \Magento\Framework\Registry $registry,

        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,

        \Magento\Framework\Validator\UniversalFactory $universalFactory,

        array $data = []

    ) 

    {

    	parent::__construct($context,$registry,$attrOptionCollectionFactory,$universalFactory,$data);

        $this->_registry = $registry;

        $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;

        $this->_universalFactory = $universalFactory;

    }


   public function canManageOptionDefaultOnly()

    {

        return false;

    }

	

	

   public function getReadOnly()

    {

        return false;

    }

    
}
