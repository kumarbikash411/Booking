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

namespace Ced\Booking\Model;

class Pagination {		
		public $itemsPerPage;
		public $range;
		public $currentPage;
		public $total;
		public $textNav;
		private $_navigation;		
		private $_link;
		private $_pageNumHtml;
		private $_itemHtml;

       /**
         * Constructor
         */
        public function __construct(
             \Magento\Framework\App\RequestInterface $RequestInterface
             )
        {
            
        	//set default values
        	$this->total = 0;	
			//private values
			$this->_navigation  = array(
					'next'=>'Next',
					'pre' =>'Pre',
					'ipp' =>'Item per page'
			);
            $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
            		
        	$this->_link = $urlInterface->getCurrentUrl();  
        	$this->_pageNumHtml  = '';
        	$this->_itemHtml 	 = '';
            $this->_request = $RequestInterface;

            
        }
        
        
		public function paginate()
		{
            $this->itemsPerPage = 2;
            $this->range        = 1;
            $this->currentPage  = 1;        
            
            $this->textNav      = true;
            $this->itemSelect   = array(2,25,50,100,'All'); 

            $getdata = $this->_request->getParams();
			//get current page
			if(isset($getdata['current'])){
				$this->currentPage  = $getdata['current'];		
			}			
			//get item per page
			if(isset($getdata['item'])){
				$this->itemsPerPage = $getdata['item'];
			}			
			//get page numbers
			$this->_pageNumHtml = $this->_getPageNumbers();			
			//get item per page select box
			$this->_itemHtml	= $this->_getItemSelect();	
		}
				
        public function pageNumbers()
        {
        	if(empty($this->_pageNumHtml)){
        		exit('Please call function paginate() first.');
        	}
        	return $this->_pageNumHtml;
        }
        
        /**
         * return jump menu in a format of select box
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        public function itemsPerPage()
        {          
        	if(empty($this->_itemHtml)){
        		exit('Please call function paginate() first.');
        	}
        	return $this->_itemHtml;	
        } 
        
       	/**
         * return page numbers html formats
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        private function  _getPageNumbers()
        {
        	$html  = '<ul>'; 
        	//previous link button
			if($this->textNav&&($this->currentPage>1)){
				echo '<li><a href="'.$this->_link .'?current='.($this->currentPage-1).'"';
				echo '>'.$this->_navigation['pre'].'</a></li>';
			}        	
        	//do ranged pagination only when total pages is greater than the range
        	if($this->total > $this->range){				
				$start = ($this->currentPage <= $this->range)?1:($this->currentPage - $this->range);
				$end   = ($this->total - $this->currentPage >= $this->range)?($this->currentPage+$this->range): $this->total;
        	}else{
        		$start = 1;
				$end   = $this->total;
        	}    
        	//loop through page numbers
        	for($i = $start; $i <= $end; $i++){
					echo '<li><a href="'.$this->_link .'?current='.$i.'"';
					if($i==$this->currentPage) echo "class='current'";
					echo '>'.$i.'</a></li>';
			}        	
        	//next link button
        	if($this->textNav&&($this->currentPage<$this->total)){
				echo '<li><a href="'.$this->_link .'?current='.($this->currentPage+1).'"';
				echo '>'.$this->_navigation['next'].'</a></li>';
			}
        	$html .= '</ul>';
        	return $html;
        }
		
        /**
         * return item select box
         *
         * @author              The-Di-Lab <thedilab@gmail.com>
         * @access              public
         * @return              string
         */
        private function  _getItemSelect()
        {
        	$items = '';
   			$ippArray = $this->itemSelect;   			
   			foreach($ippArray as $ippOpt){   
		    	$items .= ($ippOpt == $this->itemsPerPage) ? "<option selected value=\"$ippOpt\">$ippOpt</option>\n":"<option value=\"$ippOpt\">$ippOpt</option>\n";
   			}   			
	    	return "<span class=\"paginate\">".$this->_navigation['ipp']."</span>
	    	<select class=\"paginate\" onchange=\"window.location='$this->_link?current=1&item='+this[this.selectedIndex].value;return false\">$items</select>\n";   	
        }
}