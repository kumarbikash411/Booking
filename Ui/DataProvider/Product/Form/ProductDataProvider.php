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
 * @category  Ced
 * @package   Ced_booking
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Ui\DataProvider\Product\Form;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
/**
 * DataProvider for product edit form
 */
class ProductDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\Form\ProductDataProvider
{
    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PoolInterface $pool
     * @param array $meta
     * @param array $data
     */
    public function _construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        //\Ced\Booking\Model\ResourceModel\CatalogProduct\Collection $collection,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection;
        $this->pool = $pool;
    }

    /**
     * @return array
     */

    public function getData()
    {
        parent::getData();

        $productdata = $this->collection->getData();
        $getProductData = $this->data;
        if (!empty($productdata)) {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          
            foreach ($getProductData as  $key => $value) {
                foreach ($booking_data as $key1 => $data) {
                    
                    $getProductData[$key]['booking_type'] = $data->getBookingType();
                    $getProductData[$key]['booking_room_types'] =  $data->getBookingRoomTypes();
                    $getProductData[$key]['booking_service_start'] =  $data->getBookingServiceStart();
                    $getProductData[$key]['booking_service_end'] =  $data->getBookingServiceEnd();
                    $getProductData[$key]['booking_min_days'] =  $data->getBookingMinDays();
                    $getProductData[$key]['booking_max_days'] =  $data->getBookingMaxDays();
                    $getProductData[$key]['email'] =  $data->getEmail();
                    $getProductData[$key]['phone_no'] =  $data->getPhoneNo();
                    $getProductData[$key]['address'] =  $data->getAddress();
                    $getProductData[$key]['city'] =  $data->getCity();
                    $getProductData[$key]['zip'] =  $data->getZip();
                    $getProductData[$key]['country'] =  $data->getCountry();
                    $getProductData[$key]['state'] =  $data->getState();
                }
            }
        $this->data = $getProductData;
       }
        return $this->data;
    }

}
