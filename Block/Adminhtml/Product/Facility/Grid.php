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


namespace Ced\Booking\Block\Adminhtml\Product\Facility;

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
        $booking_type = $this->getRequest()->getParam('booking_type');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if($booking_type == 'hotel') {
            $collection = $objectManager->create('Ced\Booking\Model\Facilities')->getCollection()->addFieldToFilter('facility_booking_type','hotel');
        } else {
            $collection = $objectManager->create('Ced\Booking\Model\Facilities')->getCollection()->addFieldToFilter('facility_booking_type','rent');;
        }
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
            'facility_id',
            [
                'header' => __('Facilities Id'),
                'type' => 'number',
                'index' => 'facility_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'facility_title',
            [
                'header' => __('Facility Title'),
                'type' => 'text',
                'index' => 'facility_title',
            ]
        );
        
        
        $this->addColumn(
        		'facility_image',
        		[
        		'header' => __('Facility Image'),
        		'type' => 'image',
        		'index' => 'facility_image',
                'filter' => false,
        		'renderer' => 'Ced\Booking\Block\Adminhtml\Facilities\FacilityRender\Grid\Renderer\Image'
        		]
        );
        
        
        $this->addColumn(
        	'facility_status',
        	[
        		'header' => __('Facility Status'),
        		'index' => 'facility_status',
        		'type' => 'text',
                'renderer' => 'Ced\Booking\Block\Adminhtml\Facilities\FacilityRender\Grid\Renderer\Status'
        		]
        );
        $this->addColumn(
        		'facility_desc',
        		[
        		'header' => __('Description'),
        		'index' => 'facility_desc',
        		'type' => 'text'
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