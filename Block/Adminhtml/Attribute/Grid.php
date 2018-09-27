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


namespace Ced\Booking\Block\Adminhtml\Attribute;

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
        array $data = []
    ) {
    	 
        $this->moduleManager = $moduleManager;
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
        $entityTypeId = $objectManager->create ( 'Magento\Eav\Setup\EavSetup' )->getEntityTypeId ( 'catalog_product' );
        $collection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection')->setEntityTypeFilter($entityTypeId);
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
            'attribute_id',
            [
                'header' => __('Attribute Id'),
                'type' => 'number',
                'index' => 'attribute_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        
      
        $this->addColumn(
            'title',
            [
                'header' => __('Attribute Code'),
        		'type' => 'text',
                'index' => 'attribute_code',
            ]
        );
        
        $this->addColumn(
        		'backend_type',
        		[
        				'header' => __('Attribute Type'),
        				'type' => 'text',
        				'index' => 'backend_type',
        				 
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