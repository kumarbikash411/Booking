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
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Controller\Booking;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class SearchByCategory extends \Magento\Framework\App\Action\Action
{
    /**
     * @param resultPageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param resultRedirectFactory
     */
    
    protected $resultRedirectFactory;
    
    /**
     * @param resultForwardFactory
     */
    
    protected $resultForwardFactory;
    
    /**
     * @param resultRedirect
     */
    
    protected $resultRedirect;
    
    /**
     * @param Magento\Framework\App\Action\Context $context
     * @param Magento\Framework\View\Result\PageFactory
     * @param Magento\Backend\Model\View\Result\Redirect
     * @param Magento\Framework\Controller\Result\ForwardFactory
     */
    public function __construct(\Magento\Framework\App\Action\Context $context,
    		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
    		\Magento\Backend\Model\View\Result\Redirect $resultRedirectFactory,
    		\Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
            JsonFactory $resultJson
    		)
    {
    	parent::__construct($context);
    	$this->_objectManager = $context->getObjectManager();
    	$this->resultPageFactory = $resultPageFactory;
    	$this->resultForwardFactory= $resultForwardFactory;
        $this->_resultJsonFactory = $resultJson;
    }
    
    /**
     * @param execute
     */
    public function execute()
    {
      $params = $this->_request->getParams();
      print_r($params); die;
      // $post = $this->_request->getPost();

      //  foreach ($post as $key=> $pdata) {
      //     if ($key == 'sort_by') {
      //       $postdata = $post[$key];
      //     }
      //  } 

      //  $resultPage = $this->resultPageFactory->create();
      //  // if (isset($params['search-booking']) || isset($params['p']) {
      //     // if (isset($params['booking_from_price'])  && $params['booking_from_price'] != '' && $params['booking_to_price'] != '') { 
      //     //   $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\FindBookings')
      //     //          ->setData(['paramsdata'=>$params])
      //     //          ->setTemplate('Ced_Booking::search/search_booking.phtml')
      //     //          ->toHtml();
      //     // }
      //     // else if ($params['sort_by'] == "price_low_to_high") {
      //     //   $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\FindBookings')
      //     //           ->setData(['paramsdata'=>$params])
      //     //           ->setTemplate('Ced_Booking::search/search_booking.phtml')
      //     //           ->toHtml();
      //     //    // print_r($template);
      //     //    // die; 
      //     //        /*  ->setData(['paramsdata'=>$params])
      //     //          ->setTemplate('Ced_Booking::search/search_booking.phtml')
      //     //          ->toHtml();*/
      //     // }
      //     // if (isset($postdata) && $postdata == "price_high_to_low") {
      //     //   $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\FindBookings')
      //     //          ->setData(['paramsdata'=>$params])
      //     //          ->setTemplate('Ced_Booking::search/search_booking.phtml')
      //     //          ->toHtml();
      //     // }
      //     if (isset($postdata) && $postdata == "stars_high_to_low") {
                
      //         $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\SearchByStarsHighToLow')
      //                    ->setData(['post'=>$postdata])
      //                    ->setTemplate('Ced_Booking::search/search_by_stars_high_to_low.phtml')
      //                    ->toHtml();
      //       //->getCollection()->getData();

      //         // $collection = $resultPage->getLayout()->createBlock('Ced\Booking\Block\FindBookings')->getBookingList();

      //         // $data = array_merge($collection,$template);
      //         // print_r($data); die;
      //              //->setData(['paramsdata'=>$params])
      //             // ->setTemplate('Ced_Booking::search/search_booking.phtml')
      //             // ->toHtml();

      //       // $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\SearchByStarsHighToLow')
      //       //             ->setTemplate('Ced_Booking::search/search_by_stars_high_to_low.phtml')
      //       //             ->toHtml();


      //       //        ->getBookingList();

      //       // $collection = $resultPage->getLayout()->createBlock('Ced\Booking\Block\FindBookings')->getCollection()->getData();

      //       // //print_r(array_merge($collection,$template)); die;

      //       // $arr  = $productdata;
      //       // $sort = [];
      //       // foreach($arr as $k=>$v) {
      //       //   $sort['rating'][$k] = $v['rating'];
      //       // }
      //       // array_multisort($sort['rating'], SORT_DESC, $arr);


                   
      //     }
      //     else if (isset($postdata) && $postdata == "stars_low_to_high") {
      //      $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\SearchByStarsHighToLow')
      //                    ->setData(['post'=>$postdata])
      //                    ->setTemplate('Ced_Booking::search/search_by_stars_high_to_low.phtml')
      //                   ->toHtml();
      //     }
      //  // } 
      //   else if (!isset($postdata)) {

      //       $template = $resultPage->getLayout()->createBlock('Ced\Booking\Block\SearchBooking')
      //              ->setData(['paramsdata'=>$params])
      //              ->setTemplate('Ced_Booking::search/searched_bookings.phtml')
      //              ->toHtml();     
      //   }
       
      //   $resultJson =  $this->_resultJsonFactory->create();
      //   $response = ['template'=>$template];
      //   return $resultJson->setData($response);
    }
}
