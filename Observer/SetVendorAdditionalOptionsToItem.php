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
  * @category  Ced
  * @package   Ced_Booking
  * @author    CedCommerce Core Team <connect@cedcommerce.com >
  * @copyright Copyright CEDCOMMERCE (http://cedcommerce.com/)
  * @license      http://cedcommerce.com/license-agreement.txt
  */
namespace Ced\Booking\Observer; 
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;

Class SetVendorAdditionalOptionsToItem implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    protected $request;
    private $messageManager;
    
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager,
        RequestInterface $request,
        ManagerInterface $messageManager
    ) {
    
        $this->_objectManager = $objectManager;
        $this->request = $request;
        $this->messageManager = $messageManager;
    }
    /**
     *Set vendor name and url to product incart
     *
     *@param $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    { 

        $quote = $observer->getEvent()->getQuote();
        $post = $this->request->getPost();
        $item = $observer->getQuoteItem();
        $product = $item->getProduct();
        if($product->getTypeId()=='configurable') {
            return $this; 
        }
        $additionalOptions = [];
        if ($additionalOption = $product->getCustomOption('additional_options'))
        {
            $additionalOptions = (array) unserialize($additionalOption->getValue());
        }
        if ($product->getTypeId() == 'booking') {

            if (isset($post['booking-type'])) {
                if ($post['booking-type'] == 'rent_daily') {
                     $additionalOptions[] = array(
                            'code' => 'my_check_in',
                            'label' => __('Check In'),
                             'value' => $post['check-in'],
                            );
                        $additionalOptions[] = array(
                            'code' => 'my_check_out',
                            'label' => __('Check Out'),
                            'value' => $post['check-out'],
                            );
                            
                        $additionalOptions[] = array(
                            'code' => 'my_total_days',
                            'label' => __('Total Days'),
                            'value' => $post['total-days'],
                            );
                } elseif ($post['booking-type'] == 'rent_hourly') {


                    $fromTime = explode(' ',$post['check-in']);
                    $toTime = explode(' ',$post['check-out']);

                    $Service_start = $fromTime[1].' '.$fromTime[2];
                    $Service_end = $toTime[1].' '.$toTime[2];
 
                     $additionalOptions[] = array(
                            'code' => 'my_check_in',
                            'label' => __('Check In'),
                             'value' => $fromTime[0],
                            );
                        $additionalOptions[] = array(
                            'code' => 'my_check_out',
                            'label' => __('Check Out'),
                            'value' => $toTime[0],
                            );
                        $additionalOptions[] = array(
                            'code' => 'service_start',
                            'label' => __('Service Start'),
                            'value' => $Service_start,
                            );
                        $additionalOptions[] = array(
                            'code' => 'service_end',
                            'label' => __('Service Start'),
                            'value' => $Service_end,
                            );
                            
                        $additionalOptions[] = array(
                            'code' => 'my_total_days',
                            'label' => __('Total Days'),
                            'value' => $post['total-days'],
                            );
                } elseif ($post['booking-type'] == 'hotel') { 

                        $additionalOptions[] = array(
                            'code' => 'room_title',
                            'label' => __('Room Title'),
                            'value' => $post['room-title'],
                            );
                        $additionalOptions[] = array(
                            'code' => 'room_category',
                            'label' => __('Category'),
                            'type' => 'hidden',
                            'value' => $post['room-category'],
                            );
                        $additionalOptions[] = array(
                            'code' => 'my_check_in',
                            'label' => __('Check In'),
                             'value' => $post['check-in'],
                            );
                        $additionalOptions[] = array(
                            'code' => 'my_check_out',
                            'label' => __('Check Out'),
                            'value' => $post['check-out'],
                            );
                            
                        $additionalOptions[] = array(
                            'code' => 'my_total_days',
                            'label' => __('Total Days'),
                            'value' => $post['total_days'],
                            );
                }

            }
             
            //$product->addCustomOption('additional_options', serialize($additionalOptions));
              $item->addOption(
                  array(
                  'code' => 'additional_options',
                  'value' => serialize($additionalOptions),
                  )
              );
          }   
    }
}
