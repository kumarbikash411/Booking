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

namespace Ced\Booking\Model;

class RentTypeProductOrders extends \Magento\Framework\Model\AbstractModel 

{

	/**

	 *@var Magento\Framework\Registry

	 */

	

	protected $_coreRegistry;

	

	/**

	 *@var Magento\Framework\Model\Context

	 */

	

	protected $_context;

	

	/**

	 *@var Magento\Framework\UrlInterface

	 */

	

	protected $urlBuilder;

	

	/**

	 *@var Magento\Framework\ObjectManagerInterface

	 */

	

	protected $objectManager;



	/**
     * @var string
     */
    protected $_eventPrefix = 'booking_rent_order';
	

	

	/**

	 * @param Magento\Framework\ObjectManagerInterface

	 * @param Magento\Framework\Model\Context

	 * @param Magento\Framework\UrlInterface

	 * @param Magento\Framework\Registry

	 * @param Magento\Framework\Model\ResourceModel\AbstractResource

	 * @param Magento\Framework\Data\Collection\AbstractDb

	 */

	

	public function __construct(

		\Magento\Framework\Model\Context $context,

		\Magento\Framework\ObjectManagerInterface $objectManager,

		\Magento\Framework\UrlInterface  $urlBuilder,

        \Magento\Framework\Registry $registry,

        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,

        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null

		) 

	{

		$this->objectManager = $objectManager;

		$this->_context = $context;

		$this->urlBuilder = $urlBuilder;

		$this->resource = $resource;

		$this->resourceCollection = $resourceCollection;

		$this->_coreRegistry = $registry;

		parent::__construct($context,$registry,$resource,$resourceCollection);

	}

	

	/**

	 *@var construct

	 */

	protected function _construct()

	{

		$this->_init('Ced\Booking\Model\ResourceModel\RentTypeProductOrders');

	}
}