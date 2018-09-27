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

namespace Ced\Booking\Block\Adminhtml\Attribute\Edit;
 
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('id');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Room Information'));
    
    }   
    /*  */
    public function __construct(
    		Context $context,
    		EncoderInterface $jsonEncoder,
    		Session $authSession,
    		\Magento\Framework\ObjectManagerInterface $objectManager,
    		array $data = []
    )
    {
    	parent::__construct($context, $jsonEncoder, $authSession, $data);
    	$this->_objectManager = $objectManager;
    }
    protected function _beforeToHtml()
    {
    		$this->addTab('Post', array(
    				'label'     => __('Room Information'),
    				'content'   => $this->getLayout()->createBlock(
    						'Ced\Booking\Block\Adminhtml\Attribute\Edit\Tab\Main')
    				->toHtml().$this->getLayout()->createBlock(
    						'Ced\Booking\Block\Adminhtml\Attribute\Edit\Options\Options')
    				->toHtml(),
    		));
    	return parent::_beforeToHtml();
    }
    
}