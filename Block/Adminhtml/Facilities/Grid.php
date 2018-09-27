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


namespace Ced\Booking\Block\Adminhtml\Facilities;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;
 
    public function __construct(
    		
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Module\Manager $moduleManager,
    	\Ced\Booking\Model\Config\Source\Status $statuses,
        array $data = []
    ) {
    	 
        $this->moduleManager = $moduleManager;
        $this->_status = $statuses;
        parent::__construct($context, $backendHelper, $data);
    }
 
    /**
     * @return void
     */
    protected function _construct()
    {	
        parent::_construct();
        $this->setId('gridGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('grid_record');
    }
 
    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$collection = $objectManager->create('Ced\Booking\Model\Facilities')->getCollection();
    	$this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('Id'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $typeArray = [''=>' ','hotel'=>'Hotel','rent'=>'Rent','room'=>'Room'];
        $this->addColumn(
                'type',
                [
                        'header' => __('Type'),
                        'type' => 'options',
                        'index' => 'type',
                        'options' => $typeArray
                ]
            );
        
           $this->addColumn(
        		'image',
        		[
        		'header' => __('Facility Image'),
        		'type' => 'image',
        		'index' => 'image',
                'filter' => false,
        		'renderer' => 'Ced\Booking\Block\Adminhtml\Facilities\FacilityRender\Grid\Renderer\Image'
        		]
        );
        
        $this->addColumn(
            'title',
            [
                'header' => __('Facility Title'),
        		'type' => 'text',
                'index' => 'title',
            ]
        );
        $statusArray = [''=>' ','1'=>'Enabled','0'=>'Disabled'];
        $this->addColumn(
        	'status',
        	[
        		'header' => __('Facility Status'),
        		'index' => 'status',
        		'type' => 'options',
                'options' => $statusArray
        		]
        );
        $this->addColumn(
        		'description',
        		[
        		'header' => __('Description'),
        		'index' => 'description',
        		'type' => 'text'
        		]
        );
        
        $this->addColumn(
            'Edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
 
        return parent::_prepareColumns();
    }
 
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
 
        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('booking/*/massDelete'),
                'confirm' => __('Are you sure you want to delete ?')
            ]	
        );
        $statuses = $this->_status->toOptionArray();
        
        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
        		'status',
        		[
        		'label' => __('Change Status'),
        		'url' => $this->getUrl('booking/*/massStatus', ['_current' => true]),
        		'additional' => [
        		'visibility' => [
        		'name' => 'status',
        		'type' => 'select',
        		'class' => 'required-entry',
        		'label' => __('Status'),
        		'values' => $statuses
        		]
        		]
        		]
        );
 
        return $this;
    }
 
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('booking/*/grid', ['_current' => true]);
    }
 
    
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'booking/*/edit',
            ['id' => $row->getId()]
        );
    }
}