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

 * @author   	CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */



    

namespace Ced\Booking\Block\Adminhtml\Facilities\Edit\Tab;



class Facilities extends \Magento\Backend\Block\Widget\Container

{

	/**

	 * @var string

	 */

	protected $_template = 'grid/view.phtml';



	/**

	 * @param \Magento\Backend\Block\Widget\Context $context

	 * @param array $data

	 */

	public function __construct(

			\Magento\Backend\Block\Widget\Context $context,

			array $data = []

	) {

		

		parent::__construct($context, $data);

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

				$this->getLayout()->createBlock('Ced\Booking\Block\Adminhtml\Facilities\Edit\Tab\ProductGrid\Grid', 'grid.view.grid')

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

		'label' => __('Add New Post'),

		'class' => 'primary',

		'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"

				];

		$this->buttonList->add('add', $splitButtonOptions);

	}



	/**

	 *

	 *

	 * @param string $type

	 * @return string

	 */

	protected function _getCreateUrl()

	{

		return $this->getUrl(

				'booking/*/new'

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