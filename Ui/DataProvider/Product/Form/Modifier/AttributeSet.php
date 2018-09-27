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
namespace Ced\Booking\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Field;

/**
 * Add "Attribute Set" to first fieldset
 */
class AttributeSet extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AttributeSet
{
    /**
     * Sort order of "Attribute Set" field inside of fieldset
     */
    const ATTRIBUTE_SET_FIELD_ORDER = 30;

    /**
     * Set collection factory
     *
     * @var CollectionFactory
     */
    protected $attributeSetCollectionFactory;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorInterface $locator
     * @param CollectionFactory $attributeSetCollectionFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        CollectionFactory $attributeSetCollectionFactory,
        UrlInterface $urlBuilder
    ) {
        $this->locator = $locator;
        $this->attributeSetCollectionFactory = $attributeSetCollectionFactory;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Return options for select
     *
     * @return array
     */
    public function getOptions()
    {
        /** @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $collection */
        $collection = $this->attributeSetCollectionFactory->create();
        $collection->setEntityTypeFilter($this->locator->getProduct()->getResource()->getTypeId())
            ->addFieldToSelect('attribute_set_id', 'value')
            ->addFieldToSelect('attribute_set_name', 'label')
            ->setOrder(
                'attribute_set_name',
                \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection::SORT_ORDER_ASC
            );
        $data = $collection->getData();
        if ($this->locator->getProduct()->getTypeId() != 'booking')
        {
            foreach ($data as $id=>$option)
            {
                if ($option['label'] == 'Appointment Booking' || $option['label'] == 'Daily Rent Booking' || $option['label'] == 'Hotel Booking' || $option['label'] == 'Hourly Rent Booking')
                {
                    unset($data[$id]);
                }
            }
        } else if ($this->locator->getProduct()->getTypeId() == 'booking')
        {
            foreach ($data as $id=>$option)
            {
                if ($option['label'] != 'Appointment Booking' && $option['label'] != 'Daily Rent Booking' && $option['label'] != 'Hotel Booking' && $option['label'] != 'Hourly Rent Booking')
                {
                    unset($data[$id]);
                }
            }
        }

        return $data;
    }
}
