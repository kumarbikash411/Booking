<?php 

namespace Ced\Booking\Model\Config\Product;



class Attributes implements \Magento\Framework\Option\ArrayInterface
{

	protected $product;

	public function __construct( \Magento\Catalog\Model\Product $_product ) {
		$this->_product = $_product;
	}


	public function toOptionArray( $isMultiselect = false)
	{
		$options = array(array('label'=>'Browse By Facilities','value'=>'browse_by_facilities'),
					 array('label'=>'Browse By Star Rating','value'=>'browse_by_star_rating'));

       return $options;
	}
} 

