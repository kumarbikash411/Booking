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

 
namespace Ced\Booking\Block\Adminhtml\Room\Facility\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
   
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Framework\objectManagerInterface $_objectManager,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_objectManager = $_objectManager;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {

        $param = $this->getRequest()->getParams();

        $facilities = $this->_objectManager->create('Ced\Booking\Model\Facilities')->getCollection()->addFieldToFilter('type','room');

        if (isset($param['Room_Id'])) {

          $RoomAmenitiesmodel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->getCollection()->addFieldToFilter('room_id',$param['Room_Id']);

        }


        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
 
        $form->setHtmlIdPrefix('page_');
 
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('')]);

        $count = 0;
        if (count($facilities) != 0) {

          foreach ($facilities as $facility) {

                if (count($RoomAmenitiesmodel) ==0) {
                    $ar =[
                            'label' => $facility->getTitle(),
                            'name' => 'title',
                            'data-form-part' => 'product_form',
                            'onchange' => 'this.value = this.checked;'
                        ];
                }

                $flag = false;

                if (count($RoomAmenitiesmodel) !=0) {
                    foreach ($RoomAmenitiesmodel as $val) {

                        
                       if ($val->getAmenityId() == $facility->getId()) {

                          $flag = true;

                       }

                        $ar =[
                                'label' => $facility->getTitle(),
                                'name' => 'title',
                                'data-form-part' => 'product_form',
                                'onchange' => 'this.value = this.checked;'
                            ];
                       if ($flag) {

                         $ar =[
                                'label' => $facility->getTitle(),
                                'name' => 'title',
                                'data-form-part' => 'product_form',
                                'checked' =>true,
                                'onchange' => 'this.value = this.checked;'
                            ];
                        
                       }

                  }
                }
            $fieldset->addField(
                        $facility->getId(),
                        'checkbox',$ar
                        
                      );


           }
         } 
        //$form->setValues($roomdata);
        $this->setForm($form);
  
        return parent::_prepareForm();
    }
 
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Basic Settings');
    }
 
    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Basic Settings');
    }
 
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}