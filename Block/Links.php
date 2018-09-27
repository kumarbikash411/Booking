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
namespace Ced\Booking\Block;


class Links extends \Magento\Framework\View\Element\Template
{

    protected  $_helperData;
    protected  $_urlInterface;

    /**
     * Container constructor.
     * @param \Ced\SocialLogin\Model\OAuthClient\Facebook $oAuthClientFacebook
     * @param \Ced\SocialLogin\Model\OAuthClient\Google $oAuthClientGoogle
     * @param \Ced\SocialLogin\Model\OAuthClient\Twitter $oAuthClientTwitter
     * @param \Ced\SocialLogin\Model\OAuthClient\Linkedin $oAuthClientLinkedin
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(

        \Ced\Booking\Helper\Data $helperData,
        \Magento\Framework\View\Element\Template\Context $context,

        array $data = array())

    {
        $this->_helperData = $helperData;
        $this->_urlInterface = $context->getUrlBuilder();
        parent::__construct($context, $data);

    }

    public  function getAllNavLinks(){

        $links = [];
        if($this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::HOTEL_TOP_LINK_ENABLED)){
            $links['hotel'] = ['url' => $this->_urlInterface->getUrl('booking/bookings/hotel'), 'label' => $this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::HOTEL_TOP_LINK_LABEL)];
        }

        if($this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::DAILY_TOP_LINK_ENABLED)){
            $links['daily'] = ['url' => $this->_urlInterface->getUrl('booking/bookings/daily'), 'label' => $this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::DAILY_TOP_LINK_LABEL)];
        }
        if($this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::HOURLY_TOP_LINK_ENABLED)){
            $links['hourly'] = ['url' => $this->_urlInterface->getUrl('booking/bookings/hourly'), 'label' => $this->_helperData->getStoreConfigValue(\Ced\Booking\Helper\Data::HOURLY_TOP_LINK_LABEL)];
        }
        return $links;
    }
}
