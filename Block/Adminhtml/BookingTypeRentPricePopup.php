<?php

namespace Ced\Booking\Block\Adminhtml;
 
use Magento\Backend\Block\Template;


class BookingTypeRentPricePopup extends Template
{
	

	function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		array $data = []
	)
	{

		
		parent::__construct($context, $data);
	}
}