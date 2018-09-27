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

 
namespace Ced\Booking\Block\Adminhtml\Roomtype;
 
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
 
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Initialize booking post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        
    	$this->_objectId = 'id';
        $this->_blockGroup = 'Ced_Booking';
        $this->_controller = 'adminhtml_roomtype';
 		/*  added code*/
        $this->setId('facility_edit');
        $this->setUseContainer(true);
        /*end  */
        parent::_construct();
 
        //$this->buttonList->update('save', 'label', __('Save'));
        $this->addButton(
            'save',
            [
                'label' => __('Save'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']],
                ]
            ],
            1
        );

       $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        ); 

         $objId = $this->getRequest()->getParam($this->_objectId);
         if (!empty($objId)) {
         	$this->addButton(
         			'delete',
         			[
         			'label' => __('Delete'),
         			'class' => 'delete',
         			'onclick' => 'deleteConfirm(\'' . __(
         					'Are you sure you want to delete ?'
         			) . '\', \'' . $this->getDeleteUrl() . '\')'
         			]
         	);
        }
		$this->buttonList->remove('reset');

    }
 
    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
    	
    	if ($this->_coreRegistry->registry('ced_category_data')->getId()) {
            return __("Edit Post '%1'", $this->escapeHtml($this->_coreRegistry->registry('ced_category_data')->getTitle()));
        } else {
            return __('New Category');
        }
    }
 
    /**
     * Retrieve currently edited product object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
    	return $this->_coreRegistry->registry('ced_roomtype_data');
    }  
    
    /**
     * @return string
     */
   
    
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('booking/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
 
    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
    	
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}