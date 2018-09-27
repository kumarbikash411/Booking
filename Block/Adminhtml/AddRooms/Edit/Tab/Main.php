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
 
namespace Ced\Booking\Block\Adminhtml\AddRooms\Edit\Tab;

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
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
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
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categorymodel = $objectManager->create('Ced\Booking\Model\RoomTypeCategory')->getCollection();

        $model = $this->_coreRegistry->registry('ced_roomtype_data');
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
 
        $form->setHtmlIdPrefix('page_');
 
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Basic Information')]);
 
        
        $fieldset->addField('id', 'hidden', ['name' => 'id']);
     
        
         $fieldset->addField(
            'roomtype_title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'disabled' => $isElementDisabled
            ]
        );
         $fieldset->addField(
         		'room_desc',
         		'text',
         		[
         		'name' => 'room_desc',
         		'label' => __('Description'),
         		'title' => __('Description'),
         		'disabled' => $isElementDisabled
         		]
         );
    
          $arr=['1'=>'Active','2'=>'Inactive'];
          $fieldset->addField('roomtype_status',
         	'select',
         		[
         				'name' => 'status',
         				'label' => __('Status'),
         				'title' => __('Status'),
         				'disabled' => $isElementDisabled,
         
         				'values'=>  $arr
         
         		]);
            if (count($categorymodel) != 0) {
                foreach ($categorymodel as $category) {
               
                    $catarr[$category->getCategoryName()] = $category->getCategoryName();
                } 
            } else {
                $catarr = Null;
            }

          $fieldset->addField('room_category',
            'select',
                [
                        'name' => 'room_category',
                        'label' => __('Category'),
                        'title' => __('Category'),
                        'required' => true,
                        'disabled' => $isElementDisabled,
         
                        'values'=>  $catarr
         
                ]);
          $form->setValues($model->getData());
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