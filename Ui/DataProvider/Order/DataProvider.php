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
namespace Ced\Booking\Ui\DataProvider\Order;


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
        \Magento\Sales\Model\Order\Item $itemCollection,
        \Magento\Sales\Model\ResourceModel\Order\Grid\CollectionFactory $collection,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection->create();
        
        $this->itemcollection = $itemCollection;
        $this->size=sizeof($this->collection->getData());
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
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
        $itemscollection = $this->itemcollection->getCollection()->addFieldToFilter('product_type','booking');

        
        $orderId = $itemscollection->getColumnValues('order_id');
        $unique_order_id = array_unique($orderId);


        
        if (count($unique_order_id)!=0) {
            $collection = $this->getCollection()->addFieldToFilter('entity_id', $unique_order_id);
            return [
                'totalRecords' =>  count($collection),
                'items' => $collection->getData(),
            ];
        } else {
            $collection = [];
            return [
                'totalRecords' =>  count($collection),
                'items' => $collection,
            ];
        }
        


        /*$items = [];
        foreach ($unique_order_id as $key=>$id) {
            $items[] = $this->model->load($id)->getData();
        }*/

        
    }


    /**
     * Add field to select
     *
     * @param string|array $field
     * @param string|null $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }
}