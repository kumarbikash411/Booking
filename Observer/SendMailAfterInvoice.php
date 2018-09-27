<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order\Address\Renderer;

class SendMailAfterInvoice implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
  protected $_messageManager;
	/**
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	 function __construct(
			\Magento\Framework\ObjectManagerInterface $objectManager,
	 		RequestInterface $request,
			ScopeConfig $scopeConfig,
			\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
      Renderer $addressRenderer,
      \Magento\Framework\Message\ManagerInterface $messageManager

	) {
	 	
		$this->_objectManager=$objectManager;
		$this->scopeConfig = $scopeConfig;
		$this->request = $request;
		$this->_transportBuilder = $transportBuilder;
    $this->addressRenderer = $addressRenderer;
    $this->_messageManager = $messageManager;

	}

    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }

	/**
	 * customer register event handler
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
        $invoicedata = $observer->getEvent()->getInvoice()->getOrder();
        $allItems = $invoicedata->getAllItems();
        $billingAddress = $invoicedata->getBillingAddress();
        $OrderIncrementId = $invoicedata->getIncrementId();

        foreach ($allItems as $items) {
           $item[] = $items->getData();
        }
       
		   
        $receiveremail = $billingAddress->getEmail();

         try {
         $order=$this->_objectManager->get('Magento\Sales\Model\Order')->load($invoicedata->getId());

       } catch (\Exception $e) {

        echo $e->getMessage();
        die;
       }
        /* Sender Detail  */
        $sender = [
            'name' =>  'Test Sender Name',
            'email' => 'sender@addess.com',
        ];
        
        /*$bookingemailvar = [];
        $bookingemailvar['order'] = $order;*/

        $transport = [
            'order' => $order,
            'billing' => $order->getBillingAddress(),
            'store' => $order->getStore(),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
        ];
        $transport = new \Magento\Framework\DataObject($transport);
       /* $bookingemailvar['items'] = $item;
        $bookingemailvar['customer_name'] = $billingAddress->getFirstname().' '.$billingAddress->getLastname();
        $bookingemailvar['address'] = $billingAddress;
        $bookingemailvar['order_increment_id'] = $OrderIncrementId;
*/
        try{
            $transport = $this->_transportBuilder->setTemplateIdentifier('booking_email_template_new')
                                    ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                                    ->setTemplateVars($transport->getData())
                                    ->setFrom($sender)
                                    ->addTo('devcedcommerce@gmail.com')
                                    ->getTransport();               
              
            $transport->sendMessage();
            }catch(\Exception $e)
            {
            $this->_messageManager->addError($e->getMessage());
              
            }
         
        // call helper method to send mail
       /* $this->_objectManager->get('Ced\Booking\Helper\Email')->yourCustomMailSendMethod(
              $bookingemailvar,
              $sender,
              $receiver
          );*/
	} 

}


	
