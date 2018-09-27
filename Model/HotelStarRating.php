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
 * @author 		CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Model;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

use Magento\Framework\DB\Ddl\Table;

/**

* Custom Attribute Renderer

*/

class HotelStarRating extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource

{

	/**

	* @var OptionFactory

	*/

	protected $optionFactory;

	/**

	* @param OptionFactory $optionFactory

	*/

	/**

	* Get all options

	*

	* @return array

	*/

	public function getAllOptions()

	{

		/* star rating options list*/

		$this->_options=[ 
			['label'=>'--Please Select Rating--', 'value'=>' '],

			['value'=>'1', 'label'=>'1 star'],

			['value'=>'2', 'label'=>'2 star'],

			['value'=>'3', 'label'=>'3 star'],

			['value'=>'4', 'label'=>'4 star'],

			['value'=>'5', 'label'=>'5 star']

		];

		return $this->_options;

		}

}