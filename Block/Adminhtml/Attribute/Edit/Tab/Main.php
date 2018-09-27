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

 
namespace Ced\Booking\Block\Adminhtml\Attribute\Edit\Tab;
 use Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options;

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
        $RoomId = isset($param['Room_Id']) ? $param['Room_Id'] : null;


        $roomcategoryModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->getCollection();
        $roomtypesModel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->getCollection();
        $roomCataegoryRelation = $this->_objectManager->create('Ced\Booking\Model\RoomCategoryRelation')->getCollection();

        $roomCategoryJson = json_encode($roomcategoryModel->getData());
        $RoomCategoryRelationJson = json_encode($roomCataegoryRelation->getData());
        $RoomTypeJson = json_encode($roomtypesModel->getData());

        if ($RoomId != null) {
          $model = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($RoomId);

          $categorymodel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($model['room_category_id']);
          $typesmodel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($model['room_type_id']);
          $roomdata =  ['room_category'=>$categorymodel['title'],
                      'room_type'=>$typesmodel['title'],
                      'status' => $model['status'],
                      'min_booking_allowed_days' => $model['min_booking_allowed_days'],
                      'max_booking_allowed_days' => $model['max_booking_allowed_days'],
                      'price' => $model['price'],
                      'description' => $model['description'],
                      'max_allowed_child' => $typesmodel['max_allowed_child'],
                      'max_allowed_adults' => $typesmodel['min_allowed_child']
                      ];
        } else {
          $roomdata = null;
        }


        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
 
        $form->setHtmlIdPrefix('page_');
 
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('')]);
 
        
        //$fieldset->addField('attribute_id', 'hidden', ['name' => 'id']);
        $arr[''] = 'Please Select'; 

        if (count($roomcategoryModel)!=0) {

          foreach ($roomcategoryModel as $roomcat) {

            $arr[$roomcat->getTitle()] = $roomcat->getTitle();

          }
        } else {
          $arr = '';
        }

        if (count($roomtypesModel)!=0) {

          foreach ($roomtypesModel as $roomtype) {

            $roomtypedata[$roomtype->getTitle()] = $roomtype->getTitle();

          }
        } else {
          $roomtypedata = '';
        }
        
         $fieldset->addField(
            'room_category',
            'select',
            [
                'name' => 'room_category',
                'label' => __('Room Category'),
                'title' => __('Room Category'),
                'disabled' => $isElementDisabled,
                'required' => true,
                'values'=>  $arr,
                'onchange' => "a()",
         
            ]
        );

         if ($RoomId != null) {
            $fieldset->addField(
              'room_type',
              'select',
              [
                  'name' => 'room_type',
                  'label' => __('Room Type'),
                  'title' => __('Room Type'),
                  'disabled' => $isElementDisabled,
                  'required' => true,
                  'values' => $roomtypedata,
                  'onchange' => "AllowedPeople()",
           
              ]
            );
          } else {
            $fieldset->addField(
              'room_type',
              'select',
              [
                  'name' => 'room_type',
                  'label' => __('Room Type'),
                  'title' => __('Room Type'),
                  'disabled' => $isElementDisabled,
                  'required' => true,
                  'onchange' => "AllowedPeople()",
           
              ]
            );
          }

           $status=array('1'=>'Enabled','0'=>'Disabled');

          $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'room_status',
                'label' => __('Room Status'),
                'title' => __('Room Status'),
                'disabled' => $isElementDisabled,
                'required' => true,
                'values'=>  $status
         
            ]
          );

          $fieldset->addField(
              'max_allowed_child',
              'text',
              [
                  'name' => 'max_allowed_child',
                  'label' => __('Room Max Children'),
                  'title' => __('Room Max Children'),
                  'disabled' => true
              ]
          ); 

          $fieldset->addField(
              'max_allowed_adults',
              'text',
              [
                  'name' => 'max_allowed_adults',
                  'label' => __('Room Max Adults'),
                  'title' => __('Room Max Adults'),
                  'disabled' => true
              ]
          ); 

          $fieldset->addField(
              'price',
              'text',
              [
                  'name' => 'room_price',
                  'label' => __('Room Price'),
                  'title' => __('Room Price'),
                  'required' => true,
                  'disabled' => $isElementDisabled
              ]
          ); 
          $fieldset->addField(
              'min_booking_allowed_days',
              'text',
              [
                  'name' => 'min_booking_allowed_days',
                  'label' => __('Minimum Booking Allowed Days'),
                  'title' => __('Minimum Booking Allowed Days'),
                  'required' => true,
                  'disabled' => $isElementDisabled
              ]
          ); 
          $fieldset->addField(
              'max_booking_allowed_days',
              'text',
              [
                  'name' => 'max_booking_allowed_days',
                  'label' => __('Maximum Booking Allowed Days'),
                  'title' => __('Maximum Booking Allowed Days'),
                  'required' => true,
                  'disabled' => $isElementDisabled
              ]
          ); 
          $fieldset->addField(
              'description',
              'textarea',
              [
                  'name' => 'description',
                  'label' => __('Description'),
                  'title' => __('Description'),
                  'disabled' => $isElementDisabled
              ]
          );

          $user = $fieldset->addField(
              'exclude_days',
              'hidden',
              [
                  'name' => 'exclude_days',
                  'label' => __('Exclude Days'),
                  'title' => __('Exclude Days'),
                  'disabled' => $isElementDisabled
              ]
          ); 
          
          $user->setAfterElementHtml("
            <script type=\"text/javascript\">

            function a(){

              var roomCategoryJson = '".$roomCategoryJson."';
              var roomCategoryRelationJson = '".$RoomCategoryRelationJson."';
              var roomTypeJson = '".$RoomTypeJson."';

              var roomcategoryvalue = document.getElementsByName('room_category')[0].value;

              var selectlist = '<label><span>Room Type</span></label>';

                  selectlist += '<div>';

                  selectlist += '<select>';

              var result = JSON.parse(roomCategoryJson);
              var roomCategoryRelationData = JSON.parse(roomCategoryRelationJson);

              if(result.length>0)
              { 
                  for (var i=0 ; i<result.length; i++) {
                   
                    var objCategorytitle = result[i].title;
                    var objCategoryId = result[i].id;

                    if (objCategorytitle == roomcategoryvalue) 
                    {

                      if(roomCategoryRelationData.length>0)
                      { 
                          for (var j=0 ; j<roomCategoryRelationData.length; j++) {

                            var RelationCatId = roomCategoryRelationData[j].room_category_id;

                            if (objCategoryId == RelationCatId) {

                              var roomtypeId = roomCategoryRelationData[j].room_type_id;;

                              var roomTypeData = JSON.parse(roomTypeJson);

                              if(roomTypeData.length>0)
                              {
                                for (var k=0 ; k<roomTypeData.length; k++) {

                                  var TypeTitle = roomTypeData[k].title;
                                  var TypeId = roomTypeData[k].id;
                                  if (TypeId == roomtypeId) {

                                    selectlist += '<option>' + TypeTitle + '</option>';  
                                  }
                                }
                              }
                            } 
                          }
                      }
                    } 
                  }
              }
              selectlist += '</select></div>';
              document.getElementsByName('room_type')[0].innerHTML = selectlist;  

              var tyepTitle = document.getElementsByName('room_type')[0].value;  
              var roomTypeJson = '".$RoomTypeJson."';
              var resultData = JSON.parse(roomTypeJson);

              if(resultData.length>0)
              { 
                for (var i=0 ; i<resultData.length; i++) {
                  var title = resultData[i].title;
                  var maxchild = resultData[i].max_allowed_child;
                  var minchild = resultData[i].min_allowed_child;

                  if (title == tyepTitle) {

                    document.getElementsByName('max_allowed_child')[0].value = maxchild;
                    document.getElementsByName('max_allowed_adults')[0].value = minchild;

                 
                  }
                }
              }


            }

          
            document.getElementById('attribute_block').style.display='block';
            document.getElementById('room-exclude-days-block').style.display='block';


            function AllowedPeople() {

              var tyepTitle = document.getElementsByName('room_type')[0].value;  
              var roomTypeJson = '".$RoomTypeJson."';
              var resultData = JSON.parse(roomTypeJson);

              if(resultData.length>0)
              { 
                for (var i=0 ; i<resultData.length; i++) {
                  var title = resultData[i].title;
                  var maxchild = resultData[i].max_allowed_child;
                  var maxadults = resultData[i].min_allowed_child;

                  if (title == tyepTitle) {

                    document.getElementsByName('max_allowed_child')[0].value = maxchild;
                    document.getElementsByName('max_allowed_adults')[0].value = maxadults;

                 
                  }
                }
              }

            }

            </script>
          "); 

        $form->setValues($roomdata);
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