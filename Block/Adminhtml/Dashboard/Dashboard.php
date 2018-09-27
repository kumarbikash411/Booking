<?php

namespace Ced\Booking\Block\Adminhtml\Dashboard;
 
use Magento\Backend\Block\Template;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class Dashboard extends Template
{
	/**
     *
     * @var Magento\Framework\Stdlib\DateTime\Timezone 
    */
	protected $_timezone;
	/**
     *
     * @var PriceHelper
    */
	protected $_priceHelper;

	function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		Timezone $timezone,
		PriceHelper $priceHelper,
		\Magento\Framework\objectManagerInterface $_objectManager,
		array $data = []
	)
	{
		$this->_timezone = $timezone;
		$this->_objectManager = $_objectManager;
		$this->_priceHelper = $priceHelper;
		parent::__construct($context, $data);
	}

}