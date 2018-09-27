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

namespace Ced\Booking\Model\Product\Source;

class Bookingtype implements \Magento\Framework\Option\ArrayInterface
{	
	/**
	* @return array
	*/
	public function toOptionArray()
	{		
		$options[]=array('value'=>"",'label'=>"Please Select an Option");
		$options[]=array('value'=>"rent_hourly",'label'=>"Rent Hourly");
		$options[]=array('value'=>"rent_daily",'label'=>"Rent Daily");
		$options[]=array('value'=>"hotel",'label'=>"Hotel");
		return $options;

	}
	

}
