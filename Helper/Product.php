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

class BookingProduct extends AbstractHelper
{
	/**
	* @var Magento\Catalog\Model\Category
	**/
	protected $_category;
	
	public function __construct(
       Context $context,
       Category $category
    ) 
	{
       parent::__construct($context);
	   $this->_category = $category;
    }
	function getBookingCategories()
	{
		//$allowCategory = explode(',',$strAllowCategories);
		//$_category = $this->_category->load($parentId);
		/* if($_category->getIsActive())
		{
			$subcats = $_category->getChildren();
			if($subcats != '')
			{
				$prefix .= '-';
				$subcatsToArray = explode(',',$subcats);
				foreach($subcatsToArray as $sub)
				{
					if(in_array($sub,$allowCategory))
					{ */
						$subCategory = $this->_category;
						print_r($subCategory); die;
						echo '<option value="'.$subCategory->getId().'">'.$prefix.$subCategory->getName().'</option>';
						//$this->getBookingCategories($subCategory->getId());				
					
				
			
		
		//$prefix = '-';
	}
}
 