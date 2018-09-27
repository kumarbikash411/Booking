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

 * @author   CedCommerce Magento Core Team <Ced_MagentoCoreTeam@cedcommerce.com>

 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)

 * @license      http://cedcommerce.com/license-agreement.txt

 */



namespace Ced\Booking\Controller\Adminhtml\BookingProduct;

 

class AddRoom extends \Magento\Backend\App\Action

{

    /**

     * @var \Magento\Backend\Model\View\Result\Forward

     */

	

    protected $resultForwardFactory;

 

    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

    ) {

        $this->resultForwardFactory = $resultForwardFactory;

        parent::__construct($context);

    }

 

    /**

     * Forward to edit

     *

     * @return \Magento\Backend\Model\View\Result\Forward

     */

    public function execute()

    {	
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */

    	

    	$resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward('edit');

    }

 

    /**

     * {@inheritdoc}

     */

    protected function _isAllowed()

    {

        return true;

    }

}