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
use Magento\Framework\ObjectManagerInterface;


class BookingAddress extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource

{

	

	/**

	* @var OptionFactory

	*/

	protected $optionFactory;

	/**

	* @param OptionFactory $optionFactory

	*/

	public function __construct(
        ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $coreRegistry,
        \Ced\Booking\Helper\Data $bookingHelper,
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformation)

	{
        $this->_objectManager = $objectManager;
        $this->customerSession = $customerSession;
        $this->_coreRegistry = $coreRegistry;
        $this->bookingHelper = $bookingHelper;
        $this->countryInformation = $countryInformation;
	}

	/**

	* Get all options

	*

	* @return array

	*/

	public function getAllOptions()

	{
		$this->_options = [];
			
	    if ($this->bookingHelper->isModuleEnabled('Ced_CsMarketplace'))
	    { 
			$vendorId = $this->_objectManager->get('Ced\CsMarketplace\Model\Session')->getVendorId();
	    }
		
		if (isset($vendorId)) {
			$locationCollection = $this->_objectManager->create('Ced\Booking\Model\Location')->getCollection()->addFieldToFilter('vendor_id',$vendorId);

		} elseif ($this->bookingHelper->isModuleEnabled('Ced_CsBooking')) {
			$locationCollection = $this->_objectManager->create('Ced\Booking\Model\Location')->getCollection()->addFieldToFilter('vendor_id','');
		} else {
			$locationCollection = $this->_objectManager->create('Ced\Booking\Model\Location')->getCollection();
		}
		if (count($locationCollection)!=0) {
			$this->_options[] = ['label'=>'--Please select Address--',
								'value'=>' '];
			foreach ($locationCollection as $location) {
			
				$country = $this->countryInformation->getCountryInfo($location->getCountry());
                $countryNames = $country->getFullNameLocale();

				$addressLabel = $countryNames.','.$location->getState().','.$location->getCity().','.$location->getStreetAddress().','.$location->getZip();

				$this->_options[] = ['label'=>$addressLabel,
									 'value'=>$location->getId()];
			}
		}
		

		return $this->_options;

		}

}