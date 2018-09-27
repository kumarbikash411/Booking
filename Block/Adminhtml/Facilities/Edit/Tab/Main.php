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

 
namespace Ced\Booking\Block\Adminhtml\Facilities\Edit\Tab;
 

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
        $model = $this->_coreRegistry->registry('ced_facilities_data');
      
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
 
        $form->setHtmlIdPrefix('page_');
        $htmlIdPrefix = $form->getHtmlIdPrefix();

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Facility Information')]);
 
        
        $fieldset->addField('id', 'hidden', ['name' => 'id']);
     
        
         $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
         
         $arr2=array('1'=>'Enabled','0'=>'Disabled');
          $fieldset->addField('status',
         	'select',
         		[
         				'name' => 'status',
         				'label' => __('Status'),
         				'title' => __('Status'),
         				'disabled' => $isElementDisabled,
         
         				'values'=>  $arr2
         
         		]);
          $arr1=array('hotel'=>'Hotel','rent'=>'Rent','room'=>'Room');
          $fieldset->addField('type',
          		'select',
          		[
          				'name' => 'type',
          				'label' => __('Type'),
          				'title' => __('Type'),
          				'disabled' => $isElementDisabled,
          				 
          				'values'=>  $arr1
          				 
          		]);

            $yesno =array('file'=>'Image Upload','fontawesome'=>'Font Awesome Class');
            $fieldset->addField('image_type',
                'select',
                [
                    'name' => 'image_type',
                    'label' => __('Facility Image Type'),
                    'title' => __('Facility Image Type'),
                    'values'=>  $yesno

                ]);


            $fieldset->addField(
                    'image',
                    'image',
                    [
                    'name' => 'image',
                    'label' => __('Image'),
                    'title' => __('Image'),
                    'note' => '[Allow image type: jpg, jpeg, gif, png]',
                    'disabled' => $isElementDisabled
                    ]
            );
         	
            $field = $fieldset->addField(
            		'icon',
            		'text',
            		[
            		'name' 	=> 'facility_icon',
            		'title'	=> __('Use font icon instead image thumbnail?'),
            		'label' => __('Use font icon instead image thumbnail?'),
            		'value' =>__($model->getFacilityImage()),
            		'note'  => __('<span class="note-booking">
				                  Example:<br>
				                  Input "fa-desktop" for <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Fontawesome</a>,<br>
				                  Input "im-pool" for <a href="https://icomoon.io/" target="_blank"> Icomoon</a>,<br>
				                  </span>')
            		]
            );
            $fieldset->addField(
            		'description',
            		'textarea',
            		[
            		'name' => 'description',
            		'label' => __('Description'),
            		'title' => __('Description'),
            		//'required' => true,
            		'disabled' => $isElementDisabled
            		]
            );
            if (preg_match('/fa-/', $model->getImage()) || preg_match('/im-/', $model->getImage())) {
            	$model->setIcon($model->getImage());
            }
            $form->setValues($model->getData());

            /**set dependency **/
            $this->setChild(
                'form_after',
                $this->getLayout()->createBlock(
                    'Magento\Backend\Block\Widget\Form\Element\Dependence'
                )->addFieldMap(
                    "{$htmlIdPrefix}image",
                    'image'
                )
                    ->addFieldMap(
                        "{$htmlIdPrefix}image_type",
                        'image_type'
                    )
                    ->addFieldMap(
                        "{$htmlIdPrefix}icon",
                        'icon'
                    )
                    ->addFieldDependence(
                        'image',
                        'image_type',
                        'file'
                    )
                    ->addFieldDependence(
                        'icon',
                        'image_type',
                        'fontawesome'
                    )
            );
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
        return __('Facility Information');
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