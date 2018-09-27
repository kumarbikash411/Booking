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
class RoomDataProvider extends AbstractDataProvider
{

    protected $collection;

    public function __construct(
        \Ced\Booking\Model\ResourceModel\CatalogProduct\Collection $collection
    ) {
        $this->collection = $collection;
    }

    /**
     * @return array
     */

    public function getData()
    {
        parent::getData();
        $productdata = $this->collection->getData();
        $getProductData = $this->data;
        return $getProductData;
    }

}
