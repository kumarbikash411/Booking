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
 * @author   	CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block\Adminhtml;

class Products extends \Magento\Backend\Block\Widget\Container
{
	/**
	 * @var string
	 */
	 protected $_template = 'products/view.phtml';

	/**
	 * @param \Magento\Backend\Block\Widget\Context $context
	 * @param array $data
	 */
	public function __construct(
			\Magento\Backend\Block\Widget\Context $context,
			\Magento\Catalog\Model\Product\TypeFactory $typeFactory,
			\Magento\Catalog\Model\Product\AttributeSet\Options $attributeSet,
			\Magento\Framework\UrlInterface $urlBuilder,
			array $data = []
	) {				
		parent::__construct($context, $data);
		$this->_typeFactory = $typeFactory;
		$this->_attributeSet = $attributeSet;
		$this->urlBuilder = $urlBuilder;
		$this->_getAddButtonOptions();
	}

	/**
	 * Prepare button and gridCreate Grid , edit/add grid row and installer in Magento2
	 *
	 * @return \Magento\Catalog\Block\Adminhtml\Product
	 */
	protected function _prepareLayout()
	{	
		$this->setChild(
				'grid',
				$this->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Products\Grid', 'grid.view.gridhotel')
		);
		return parent::_prepareLayout();
	}

	/**
	 *
	 *
	 * @return array
	 */
	protected function _getAddButtonOptions()
	{
		$splitButtonOptions = [
		'label' => __('Add Booking Product'),
		'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
		'options' => $this->_getAddProductButtonOptions()
				];
		$this->buttonList->add('add', $splitButtonOptions);
		
	}

	 /**
     * Retrieve options for 'Add Product' split button
     *
     * @return array
     */
    protected function _getAddProductButtonOptions()
    {
        $splitButtonOptions = [];
        $types = $this->_typeFactory->create()->getTypes();
        uasort(
            $types,
            function ($elementOne, $elementTwo) {
                return ($elementOne['sort_order'] < $elementTwo['sort_order']) ? -1 : 1;
            }
        );
        $attributeSetArray = $this->_attributeSet->toOptionArray();

        foreach ($attributeSetArray as $key=>$set)
        {
        	if ($set['label'] == 'Hotel Booking' || $set['label'] == 'Daily Rent Booking' || $set['label'] == 'Hourly Rent Booking' || $set['label'] == 'Appointment Booking') 
        	{

	        	$splitButtonOptions[$key] = [
	                'label' => __($set['label']),
	                'onclick' => "setLocation('".$this->urlBuilder->getUrl('catalog/product/new', array('set' => $set['value'],'type'=>'booking','product_type'=>'booking'))."')",
	           ];
	       }
        }

        return $splitButtonOptions;
    }

	protected function _getCreateUrl()
	{
		return $this->getUrl(
				'catalog/product/new',array('set'=>4,'type'=>'booking')
		); 
	}

	/**
	 * Render grid
	 *
	 * @return string
	 */
	public function getGridHtml()
	{
		return $this->getChildHtml('grid');
	}
}