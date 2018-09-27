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
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Booking\Controller\Adminhtml\Location;

class Regionlist extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    { 
        $this->_countryFactory = $countryFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
		$result = [];
        $countrycode = $this->getRequest()->getParam('country_id');
        $region = $this->getRequest()->getParam('region');
        if ($countrycode != '') {
            $statearray =$this->_countryFactory->create()->setId($countrycode)->getLoadedRegionCollection()->toOptionArray();
           if (sizeof($statearray) > 0) {
                $state = "<option value=''>--Please Select--</option>";
                foreach ($statearray as $_state) {
                    if($_state['value']){
                        if ($region!='' && $region==$_state['label']) {
                            $state .= "<option selected=selected>" . $_state['label'] . "</option>";
                        } else {
                            $state .= "<option value='".$_state['label']."'>" . $_state['label'] . "</option>";
                        }
                    }
               }
               $result['htmlconent'] = $state;
               $result['hasstate'] = 1;
            }else{
                $result['hasstate'] = 0;
                if ($region) {
                    $result['htmlconent'] = '<input id="region_id" name="collage_step1_select_country" title="Store Region Id" class="input-text" type="text" value="'.$region.'">';
                } else {
                    $result['htmlconent'] = '<input id="region_id" name="collage_step1_select_country" title="Store Region Id" class="input-text" type="text">';
                }
               
            }
        }
         $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    } 

  }