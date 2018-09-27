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
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Model\Category;

class SendEmailAfterPlaceOrder extends AbstractHelper
{
  /**
  * @var Magento\Catalog\Model\Category
  **/
  protected $_category;


  protected $_messageManager;
  
  public function __construct(
       Context $context,
       Category $category,
       \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
       \Magento\Framework\Mail\Transport $transport,
       \Magento\Framework\Message\ManagerInterface $messageManager

    ) 
  {
    parent::__construct($context);
    $this->_category = $category;
    $this->_transportBuilder = $transportBuilder;
    $this->_messageManager  = $messageManager;
    $this->_transport = $transport;
    $this->_scopeConfig = $context->getScopeConfig();

    }


    public function Sendmailtoadmin($booking_items,$order)
    {
          /* Sender Detail  */
          $sender = [
              'name' =>  'Admin email',
              'email' => 'admin@cedcommerce.com',
          ];

          $receiver = $this->_scopeConfig->getValue('booking/notification_settings/admin_email_address');
          
          $orderdata = [ 'booking_items'=>$booking_items,
                          'increment_id'=>$order->getIncrementId(),
                          'customer_name'=>$order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname(),
                          'grand_total'=>$order->getGrandTotal()
                        ];

          try{
              $transport = $this->_transportBuilder->setTemplateIdentifier('send_booking_email_to_customer_template')
                                      ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                                      ->setTemplateVars($orderdata)
                                      ->setFrom($sender)
                                      ->addTo($receiver)
                                      ->getTransport();               
              $transport->sendMessage();

              }catch(\Exception $e)
              {
                // var_dump($e->getMessage()); die("admin");
                $this->_messageManager->addError('Unable to send mail to admin');
              }
    }
 
  public function Sendmailtocustomer($booking_items,$order)
  {
        /* Sender Detail  */
        $sender = [
            'name' =>  'Customer email',
            'email' => 'Customer@cedcommerce.com',
        ];
        $receiver = $order->getCustomerEmail();
       // var_dump($receiver); die;
        $orderdata = [ 'booking_items'=>$booking_items,
                        'increment_id'=>$order->getIncrementId(),
                        'customer_name'=>$order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname(),
                        'grand_total'=>$order->getGrandTotal()
                      ];
        try{
            $transport = $this->_transportBuilder->setTemplateIdentifier('send_booking_email_to_customer_template')
                                    ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                                    ->setTemplateVars($orderdata)
                                    ->setFrom($sender)
                                    ->addTo($receiver)
                                    ->getTransport();               
            $transport->sendMessage();
            }catch(\Exception $e)
            {
              //var_dump($e->getMessage()); die("customer");
              $this->_messageManager->addError('Unable to send mail to customer');
            }
  } 
}
 