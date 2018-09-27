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

namespace Ced\Booking\Model;
 
use Ced\Booking\Model\ResourceModel\Location\Collection;
 
class LocationDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;
    /**
     * @var Collection
     */
    protected $collection;
    /**
     * @var array
     */
    protected $addFieldStrategies;

    /**
     * @var array
     */
    protected $addFilterStrategies;

    /**
     * FormDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Collection $collectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collectionFactory,
        \Magento\Framework\Registry $registry,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory;
        $this->_coreRegistry = $registry;
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
        $data = $this->_coreRegistry->registry('ced_location_data');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if(isset($data['id'])) {
            $id = $data['id'];

            $model = $objectManager->create('Ced\Booking\Model\Location')->load($id)->toArray();
            $arr = [$id => ['booking_location' => []]];
            foreach ($model as $key => $value) {
                if ($key == 'state') {
                    $arr[$id]['booking_location']['region_id'] = $value;
                } else {
                    $arr[$id]['booking_location'][$key] = $value;
                }
                
            }
        }
        else{

            $arr = [];
        }
        return $arr; 
    
    }
}