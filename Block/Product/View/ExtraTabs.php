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
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Block\Product\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Ced\Booking\Model\ProductFacilityRelationFactory;


class ExtraTabs extends \Ced\Booking\Block\AbstractBooking
{  
	
   public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductFacilityRelationFactory $ProductFacilityRelation,
        \Magento\Framework\Registry $coreRegistry,
        Timezone $timezone,
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformation,
    	array $data=[]
    )
    {
        parent::__construct($context, $coreRegistry, $data);
        $this->_productFacilityRelation = $ProductFacilityRelation;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_timeZone = $timezone;
        $this->scopeConfig = $context->getScopeConfig();
        $this->countryInformation = $countryInformation; 
    }

    public  function getLocationInfo(){
        $data = [];
        $product = $this->getProduct();
        $locationData = $this->_objectManager->create('Ced\Booking\Model\Location')->load($product->getAddress())->getData();
        if (count($locationData)!=0) {
            foreach ($locationData as $key=>$location) {

                if ($key != 'id' && $key != 'vendor_id' && $location!='') {

                    if ($key == 'street_address') {
                        $key = __('Street Address');
                    }

                    if ($key == 'country') {

                        $country = $this->countryInformation->getCountryInfo($location);
                        $countryName = $country->getFullNameLocale();
                        $location = $countryName;
                    }

                    $data[] = [
                            'label' => __($key),
                            'value' => $location
                        ];
                }

                
            }
        }
        return $data;

        // $attributes = $product->getAttributes();
        // $allowedAttributes =  \Ced\Booking\Helper\Data::LOCATION_ATTRIBUTES;
        // foreach ($attributes as $attribute) {
        //     if (in_array($attribute->getAttributeCode(), $allowedAttributes)) {
        //         $value = $attribute->getFrontend()->getValue($product);

        //         if (!$product->hasData($attribute->getAttributeCode())) {
        //             $value = __('N/A');
        //         } elseif ((string)$value == '') {
        //             $value = __('No');
        //         } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
        //             $value = $this->priceCurrency->convertAndFormat($value);
        //         }

        //         if (is_string($value) && strlen($value)) {
        //             $data[$attribute->getAttributeCode()] = [
        //                 'label' => __($attribute->getStoreLabel()),
        //                 'value' => $value,
        //                 'code' => $attribute->getAttributeCode(),
        //             ];
        //         }
        //     }
        // }
        // return $data;

    }



}
