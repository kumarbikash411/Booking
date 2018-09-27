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

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\Catalog\Model\Product\Type;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\ConfigurableProduct\Ui\DataProvider\Product\Form\Modifier\Data\AssociatedProducts;
use Magento\Catalog\Ui\AllowedProductTypes;

/**
 * Data provider for Configurable products
 */
class Composite extends AbstractModifier
{
    /**
     * @var array
     */
    private $modifiers = [];

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var AssociatedProducts
     */
    private $associatedProducts;

    /**
     * @var AllowedProductTypes
     */
    protected $allowedProductTypes;

    /**
     * @param LocatorInterface $locator
     * @param ObjectManagerInterface $objectManager
     * @param AssociatedProducts $associatedProducts
     * @param AllowedProductTypes $allowedProductTypes,
     * @param array $modifiers
     */
    public function __construct(
        LocatorInterface $locator,
        ObjectManagerInterface $objectManager,
        AssociatedProducts $associatedProducts,
        AllowedProductTypes $allowedProductTypes,
        array $modifiers = []
    ) {
        $this->locator = $locator;
        $this->objectManager = $objectManager;
        $this->associatedProducts = $associatedProducts;
        $this->allowedProductTypes = $allowedProductTypes;
        $this->modifiers = $modifiers;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta['test_fieldset_name'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Label For Fieldset'),
                        'sortOrder' => 50,
                        'collapsible' => true
                    ]
                ]
            ],
            'children' => [
                'test_field_name' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'options' => [
                                    ['value' => 'test_value_1', 'label' => 'Test Value 1'],
                                    ['value' => 'test_value_2', 'label' => 'Test Value 2'],
                                    ['value' => 'test_value_3', 'label' => 'Test Value 3'],
                                ],
                                'visible' => 1,
                                'required' => 1,
                                'label' => __('Label For Element')
                            ]
                        ]
                    ]
                ]
            ]
        ];

    return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }
}
