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
namespace Ced\Booking\Ui\DataProvider\Product\Rent\Facility;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $addFieldStrategies;


    protected $addFilterStrategies;
    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Pincode $collectionFactory
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[] $addFieldStrategies
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Ced\Booking\Model\ResourceModel\Facilities\CollectionFactory $collection,
        \Magento\Framework\App\RequestInterface $RequestInterface,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection->create();
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->_request = $RequestInterface;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
    	if (!$this->getCollection()->isLoaded()) {
    		$this->getCollection()->load();
    	}
        $itemscollection = $this->getCollection()->addFieldToFilter('status',1)->addFieldToFilter('type','rent');
    	return [
    			'totalRecords' =>  count($itemscollection),
                'items' => $itemscollection->getData(),
    	];
    }



}