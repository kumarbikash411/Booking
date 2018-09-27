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


namespace Ced\Booking\Block\Adminhtml\Products;

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
 

    protected function _prepareCollection()
    {


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product\Collection')->addAttributeToFilter('type_id','booking')->addAttributeToSelect('name')->addAttributeToSelect('sku')->addAttributeToSelect('entity_id')->addAttributeToSelect('thumbnail')->addAttributeToSelect('type_id')->addAttributeToSelect('status');
        
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
            'entity_id',
            [
                'header' => __('Product Id'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        
        $this->addColumn(
                'name',
                [
                        'header' => __('Name'),
                        'type' => 'text',
                        'index' => 'name',
                        'header_css_class' => 'col-id',
                        'column_css_class' => 'col-id'
                ]
                );
        $this->addColumn(
            'sku',
            [
                'header' => __('sku'),
                'type' => 'text',
                'index' => 'sku',
            ]
        );
        
        $this->addColumn(
                'thumbnail',
                [
                'header' => __('Image'),
                'type' => 'text',
                'index' => 'thumbnail',
                'renderer' => 'Ced\Booking\Block\Adminhtml\Product\Category\Grid\Renderer\Image'
                ]
        ); 

        $this->addColumn(
            'type_id',
            [
                'header' => __('Type'),
                'type' => 'text',
                'index' => 'type_id',
            ]
        );

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attributeSets = $objectManager->create('Magento\Catalog\Model\Product\AttributeSet\Options')->toOptionArray();
        $attributeSetArr =[];
        foreach ($attributeSets as $set) {
            if ($set['label'] != 'Default') {
                $attributeSetArr[$set['value']] = $set['label'];
            }
        }

        $this->addColumn(
            'attribute_set_id',
            [
                'header' => __('Attibute Set'),
                'type' => 'options',
                'index' => 'attribute_set_id',
                'options' => $attributeSetArr,
                // 'renderer' => 'Ced\Booking\Block\Adminhtml\Product\Category\Grid\Renderer\AttributeSet'
            ]
        );
        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'type' => 'options',
                'index' => 'status',
                'options' => ['1'=>'Enabled','2'=>'Disabled']
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
                            'base' => 'catalog/product/edit'
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
            'catalog/product/edit',
            ['id' => $row->getId()]
        );
    }
}