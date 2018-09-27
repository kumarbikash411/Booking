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
* @author      CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>
* @copyright   Copyright CedCommerce (http://cedcommerce.com/)
* @license      http://cedcommerce.com/license-agreement.txt
*/ 
namespace Ced\Booking\Controller\Adminhtml\Facilities;


class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * 
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context
       
    ) {
        parent::__construct($context);
    }

    /**
     * Update roomtype status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest ()->getParams ();
		if ($data) {
			
			$postData = $this->getRequest ()->getPost ();
			
			$ids = $this->getRequest ()->getParam ( 'id' );

			$status = $this->getRequest ()->getParam ( 'status' );

			foreach ( $ids as $id ) {
				
				$facilitydata = $this->_objectManager->create ( 'Ced\Booking\Model\Facilities' )->load ( $id );
		
				$facilitydata->setData ( 'status', $status )->save ();
			}   
        }   
        return $this->_redirect('booking/facilities/index');
    }
}
