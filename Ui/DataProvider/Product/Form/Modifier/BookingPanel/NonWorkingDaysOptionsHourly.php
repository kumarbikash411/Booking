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

namespace Ced\Booking\Ui\DataProvider\Product\Form\Modifier\BookingPanel;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductOptions\ConfigInterface;
use Magento\Catalog\Model\Config\Source\Product\Options\Price as ProductOptionsPrice;
use Magento\Framework\UrlInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Modal;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\ActionDelete;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Date;
use Magento\Framework\Locale\CurrencyInterface;

/**
 * Data provider for "Customizable Options" panel
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NonWorkingDaysOptionsHourly extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    /**#@+
     * Group values
     */
    const GROUP_NAME = 'booking-general-information';
    const GROUP_CUSTOM_OPTIONS_SCOPE = 'data.product';
    const GROUP_CUSTOM_OPTIONS_PREVIOUS_NAME = 'search-engine-optimization';
    const GROUP_CUSTOM_OPTIONS_DEFAULT_SORT_ORDER = 31;
    /**#@-*/

    /**#@+
     * Button values
     */
    const BUTTON_ADD = 'button_add';
    const BUTTON_IMPORT = 'button_import';
    /**#@-*/

    /**#@+
     * Container values
     */
    const CONTAINER_HEADER_NAME = 'non_working_rule_header';
    const CONTAINER_OPTION = 'container_option';
    const CONTAINER_COMMON_NAME = 'container_common';
    const CONTAINER_TYPE_STATIC_NAME = 'container_type_static';
    /**#@-*/

    /**#@+
     * Grid values
     */
    const GRID_OPTIONS_NAME = 'non_working_days';
    const GRID_TYPE_SELECT_NAME = 'values';
    const INTERVAL_OPTIONS_NAME = 'interval_options_time';
    /**#@-*/

    /**#@+
     * Field values
     */
    const FIELD_ENABLE = 'affect_product_custom_options';
   // const FIELD_START_DATE_NAME = 'start_date';
    const FIELD_END_DATE_NAME = 'end_date';
    const FIELD_SELECT_NAME = 'days';
    const FIELD_OPTION_ID = 'option_id';
    const FIELD_TITLE_NAME = 'title';
    const FIELD_TYPE_NAME = 'type';
    const FIELD_IS_REQUIRE_NAME = 'is_require';
    const FIELD_SORT_ORDER_NAME = 'sort_order';
    const FIELD_PRICE_NAME = 'price';
     const FIELD_DAYS_NAME = 'days';
     const FIELD_DAYS_TYPE_NAME = 'days';
    const FIELD_PRICE_TYPE_NAME = 'price_type';
    const FIELD_SKU_NAME = 'sku';
    const FIELD_MAX_CHARACTERS_NAME = 'max_characters';
    const FIELD_FILE_EXTENSION_NAME = 'file_extension';
    const FIELD_IMAGE_SIZE_X_NAME = 'image_size_x';
    const FIELD_IMAGE_SIZE_Y_NAME = 'image_size_y';
    const FIELD_IS_DELETE = 'is_delete';
    const FIELD_DATE_START_NAME = 'start_date';
    const FIELD_DATE_END_NAME = 'end_date';
    const FIELD_START_TIME_NAME = 'appointment_start_time';
    const FIELD_END_TIME_NAME = 'appointment_end_time';
    //const NON_WORKING_DAY_TYPE = 'day_type';
    const FIELD_NON_WORKING_DAYS_OPTIONS_NAME = 'non_working_day_type';
    /**#@-*/

    /**#@+
     * Import options values0
     */
    const IMPORT_OPTIONS_MODAL = 'import_options_modal';
    const CUSTOM_OPTIONS_LISTING = 'product_custom_options_listing';
    /**#@-*/

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $locator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ProductOptions\ConfigInterface
     */
    protected $productOptionsConfig;

    /**
     * @var \Magento\Catalog\Model\Config\Source\Product\Options\Price
     */
    protected $productOptionsPrice;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    
    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var CurrencyInterface
     */
    private $localeCurrency;


    protected $_jsonHelper;

    /**
     * @param LocatorInterface $locator
     * @param StoreManagerInterface $storeManager
     * @param ConfigInterface $productOptionsConfig
     * @param ProductOptionsPrice $productOptionsPrice
     * @param UrlInterface $urlBuilder
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        LocatorInterface $locator,
        StoreManagerInterface $storeManager,
        ConfigInterface $productOptionsConfig,
        ProductOptionsPrice $productOptionsPrice,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->locator = $locator;
        $this->storeManager = $storeManager;
        $this->productOptionsConfig = $productOptionsConfig;
        $this->productOptionsPrice = $productOptionsPrice;
        $this->urlBuilder = $urlBuilder;
        $this->arrayManager = $arrayManager;
        $this->_jsonHelper = $jsonHelper;
        $this->_objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $nonWorkingDays =  $this->locator->getProduct()->getData('non_working_days');
        $options = [];
        if($nonWorkingDays){
            $options = $this->_jsonHelper->jsonDecode($nonWorkingDays);
            
            array_walk_recursive( $options,
                  function(&$v, $k) { 

                    if($k == 'appointment_start_time' || $k == 'appointment_end_time'){
                        $v = $v;
                    }
                      }
                  );
        }

        return array_replace_recursive(
            $data,
            [
                $this->locator->getProduct()->getId() => [
                    static::DATA_SOURCE_DEFAULT => [
                        static::FIELD_ENABLE => 1,
                        static::GRID_OPTIONS_NAME => $options
                    ]
                ]
            ]
        );
    }

    /**
     * Format float number to have two digits after delimiter
     *
     * @param string $path
     * @param array $data
     * @return array
     */
    protected function formatPriceByPath($path, array $data)
    {
        $value = $this->arrayManager->get($path, $data);

        if (is_numeric($value)) {
            $data = $this->arrayManager->replace($path, $data, $this->formatPrice($value));
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($this->locator->getProduct()->getAttributeSetId())->getAttributeSetName();
        if ($attribute_set_name == 'Hourly Rent Booking') { 
            $this->createCustomOptionsPanel();
        }

        return $this->meta;
    }

    /**
     * Create "Customizable Options" panel
     *
     * @return $this
     */
    protected function createCustomOptionsPanel()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Booking General Information'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::GROUP_CUSTOM_OPTIONS_SCOPE,
                                'collapsible' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::CONTAINER_HEADER_NAME => $this->getHeaderContainerConfig(300),
                        //static::FIELD_ENABLE => $this->getEnableFieldConfig(310),
                        static::GRID_OPTIONS_NAME => $this->getOptionsGridConfig(320)
                    ]
                ]
            ]
        );

        return $this;
    }

    /**
     * Get config for header container
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getHeaderContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Container::NAME,
                        'componentType' => Container::NAME,
                        'template' => 'ui/form/components/complex',
                        'sortOrder' => $sortOrder,
                        'content' => __('Non working rule'),
                    ],
                ],
            ],
            'children' => [
                static::BUTTON_ADD => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'title' => __('Add Non working rules'),
                                'formElement' => Container::NAME,
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/form/components/button',
                                'sortOrder' => 20,
                                'actions' => [
                                    [
                                        'targetName' => 'ns = ${ $.ns }, index = ' . static::GRID_OPTIONS_NAME,
                                        'actionName' => 'processingAddChild',
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for the whole grid
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getOptionsGridConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Option'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Catalog/js/components/dynamic-rows-import-custom-options',
                        'template' => 'ui/dynamic-rows/templates/collapsible',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'addButton' => false,
                        'renderDefaultRecord' => false,
                        'columnsHeader' => false,
                        'collapsibleHeader' => true,
                        'sortOrder' => $sortOrder,
                        'dataProvider' => static::CUSTOM_OPTIONS_LISTING,
                        'imports' => ['insertData' => '${ $.provider }:${ $.dataProvider }'],
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'headerLabel' => __('New Option'),
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::CONTAINER_OPTION . '.' . static::FIELD_SORT_ORDER_NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::CONTAINER_OPTION => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Fieldset::NAME,
                                        'label' => null,
                                        'sortOrder' => 10,
                                        'opened' => true,
                                    ],
                                ],
                            ],
                            'children' => [
                                static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(10),
                                static::CONTAINER_COMMON_NAME => $this->getCommonContainerConfig(20),
                                static::CONTAINER_TYPE_STATIC_NAME => $this->getStaticTypeContainerConfig(30),
                                static::GRID_TYPE_SELECT_NAME => $this->getSelectTypeGridConfig(40),
                                static::INTERVAL_OPTIONS_NAME => $this->getGridIntervalOptions(50)
                                
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Get config for hidden field responsible for enabling custom options processing
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getEnableFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Field::NAME,
                        'componentType' => Input::NAME,
                        'dataScope' => static::FIELD_ENABLE,
                        'dataType' => Number::NAME,
                        'visible' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for modal window "Import Options"
     *
     * @return array
     */
    protected function getImportOptionsModalConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'options' => [
                            'title' => __('Select Product'),
                            'buttons' => [
                                [
                                    'text' => __('Import'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = ' . static::CUSTOM_OPTIONS_LISTING,
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                static::CUSTOM_OPTIONS_LISTING => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'insertListing',
                                'dataScope' => static::CUSTOM_OPTIONS_LISTING,
                                'externalProvider' => static::CUSTOM_OPTIONS_LISTING . '.'
                                    . static::CUSTOM_OPTIONS_LISTING . '_data_source',
                                'selectionsProvider' => static::CUSTOM_OPTIONS_LISTING . '.'
                                    . static::CUSTOM_OPTIONS_LISTING . '.product_columns.ids',
                                'ns' => static::CUSTOM_OPTIONS_LISTING,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'behaviourType' => 'edit',
                                'externalFilterMode' => false,
                                'currentProductId' => $this->locator->getProduct()->getId(),
                                'dataLinks' => [
                                    'imports' => false,
                                    'exports' => true
                                ],
                                'exports' => [
                                    'currentProductId' => '${ $.externalProvider }:params.current_product_id'
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for container with common fields for any type
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getCommonContainerConfig($sortOrder)
    {
        $commonContainer = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                static::FIELD_OPTION_ID => $this->getOptionIdFieldConfig(10),
                static::FIELD_TYPE_NAME => $this->getTypeFieldConfig(30),
            ]
        ];

        if ($this->locator->getProduct()->getStoreId()) {
            $useDefaultConfig = [
                'service' => [
                    'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
                ]
            ];
            $titlePath = $this->arrayManager->findPath(static::FIELD_TITLE_NAME, $commonContainer, null)
                . static::META_CONFIG_PATH;
            $commonContainer = $this->arrayManager->merge($titlePath, $commonContainer, $useDefaultConfig);
        }

        return $commonContainer;
    }

    /**
     * Get config for container with fields for all types except "select"
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getStaticTypeContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                static::FIELD_SELECT_NAME => $this->getDaysFieldConfig(10),
                static::FIELD_NON_WORKING_DAYS_OPTIONS_NAME => $this->getNonWorkingDayTypeOptions()

            ]
        ];
    }

    /**
     * Get config for grid for "select" types
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getSelectTypeGridConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Exclude Dates'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'renderDefaultRecord' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::FIELD_SORT_ORDER_NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'additionalClasses' => 'admin__control-grouped-date',

                            ],
                        ],
                    ],
                    'children' => [
                        static::FIELD_DATE_START_NAME => $this->getDateStartFieldConfig(20),
                        static::FIELD_DATE_END_NAME => $this->getDateEndFieldConfig(30),
                        static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50),
                        static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(60)
                    ]
                ]
            ]
        ];
    }


    public function getGridIntervalOptions($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Exclude Intervals'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'dataScope' => static::INTERVAL_OPTIONS_NAME,
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'renderDefaultRecord' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::FIELD_SORT_ORDER_NAME,

                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::FIELD_START_TIME_NAME => $this->getStartTimeFieldConfig(10),
                        static::FIELD_END_TIME_NAME => $this->getEndTimeFieldConfig(20), 
                        static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(30),
                        static::FIELD_IS_DELETE => $this->getIsDeleteFieldConfig(40)
                    ]
                ]
            ]
        ];
    }

    /**
     * Get config for hidden id field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getOptionIdFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Input::NAME,
                        'componentType' => Field::NAME,
                        'dataScope' => static::FIELD_OPTION_ID,
                        'sortOrder' => $sortOrder,
                        'visible' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Title" fields
     *
     * @param int $sortOrder
     * @param array $options
     * @return array
     */
    protected function getTitleFieldConfig($sortOrder, array $options = [])
    {
        return array_replace_recursive(
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Title'),
                            'componentType' => Field::NAME,
                            'formElement' => Input::NAME,
                            'dataScope' => static::FIELD_TITLE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'validation' => [
                                'required-entry' => true
                            ],
                        ],
                    ],
                ],
            ],
            $options
        );
    }

    /**
     * Get config for "Option Type" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getTypeFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Option Type'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'component' => 'Ced_CsAppointment/js/custom-options-type',
                        'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                        'selectType' => 'optgroup',
                        'dataScope' => static::FIELD_TYPE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'options' => $this->getProductOptionTypes(),
                        'disableLabel' => true,
                        'multiple' => false,
                        'width' => '500px',
                        'selectedPlaceholders' => [
                            'defaultPlaceholder' => __('-- Please select --'),
                        ],
                        'validation' => [
                            'required-entry' => true
                        ],
                        'groupsConfig' => [
                            'data' => [
                                'values' => ['date'],
                                'indexes' => [
                                    static::GRID_TYPE_SELECT_NAME,
                                ]
                            ],
                            'days' => [
                                'values' => ['days'],
                                'indexes' => [
                                    static::FIELD_SELECT_NAME,
                                ]
                            ],
                        ],
                        
                    ],
                ],
            ],
        
        ];
    }

    /**
     * Get config for "Required" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getIsRequireFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Required'),
                        'componentType' => Field::NAME,
                        'formElement' => Checkbox::NAME,
                        'dataScope' => static::FIELD_IS_REQUIRE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => '1',
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for hidden field used for sorting
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getPositionFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_SORT_ORDER_NAME,
                        'dataType' => Number::NAME,
                        'visible' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for hidden field used for removing rows
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getIsDeleteFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => ActionDelete::NAME,
                        'fit' => true,
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Price" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getDaysFieldConfig($sortOrder)
    {

        $array =            ['0' => [
                                    'value' => 'monday',
                                    'label' => 'Monday'
                                ],
                               '1' => [
                                    'value' => 'tuesday',
                                    'label' => 'Tuesday'
                                ],
                                '2' => [
                                    'value' => 'wednessday',
                                    'label' => 'Wednessday'
                                ],
                                '3' => [
                                    'value' => 'thrusday',
                                    'label' => 'Thrusday'
                                ],
                                '4' => [
                                    'value' => 'friday',
                                    'label' => 'Friday'
                                ],
                                '5' => [
                                    'value' => 'saturday',
                                    'label' => 'Saturday'
                                ],
                                '6' => [
                                    'value' => 'sunday',
                                    'label' => 'Sunday'
                                ],

                            ];
        return [
                'arguments' => [
                    'data' => [
                        'config' => [
                             'label' => __('Days'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'component' => 'Ced_CsAppointment/js/custom-options-type',
                            'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                            'dataScope' => static::FIELD_SELECT_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'options' => $array,
                            'disableLabel' => true,
                            'multiple' => false,
                            'groupsConfig' => [
                                    'day_name' => [
                                        'values' => ['monday','tuesday','wednessday','thrusday','friday','saturday','sunday'],
                                        'indexes' => [
                                            static::FIELD_NON_WORKING_DAYS_OPTIONS_NAME
                                        ]
                                    ],
                                ],
                        ],
                    ],
                ],
        ];
    }

    public function getNonWorkingDayTypeOptions()
    {   
        $dayTypeArray = [ 
                '0' => [
                        'value' => 'full_day',
                        'label' => 'Full day'
                    ],
                    '1' => [
                        'value' => 'interval',
                        'label' => 'Interval'
                    ],
                   
                ];
        return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Type'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_NON_WORKING_DAYS_OPTIONS_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => 500,
                            'options' => $dayTypeArray,
                            'component' => 'Ced_CsAppointment/js/custom-options-type',
                            'multiple' => false,
                            'disableLabel' => true,
                            'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                            'groupsConfig' => [
                                    'interval' => [
                                        'values' => ['interval'],
                                        'indexes' => [
                                            
                                            static::INTERVAL_OPTIONS_NAME,


                                        ]
                                    ],
                            ],

                        ],
                    ],
                ],
        ];

    }

    public function getDateStartFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Start Date'),
                        'componentType' => Field::NAME,
                        'formElement' => Date::NAME,
                        'dataScope' => static::FIELD_DATE_START_NAME,
                        'code' => static::FIELD_DATE_START_NAME,
                        'dataType' => Date::NAME,
                        'sortOrder' => $sortOrder,
                       'additionalClasses' => 'admin__field-date',
                    ],
                ],
            ],
        ];
    }

    public function getDateEndFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('End Date'),
                        'componentType' => Field::NAME,
                        'formElement' => Date::NAME,
                        'dataScope' => static::FIELD_DATE_END_NAME,
                        'dataType' => Date::NAME,
                        'code' => static::FIELD_DATE_END_NAME,
                        'sortOrder' => $sortOrder,
                        'additionalClasses' => 'admin__field-date',
                    ],
                ],
            ],
        ];
    }

    public function getStartTimeFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Start Time'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_START_TIME_NAME,
                        'dataType' => Text::NAME,
                        'additionalClasses' => 'appointment_start_time',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    public function getEndTimeFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('End Time'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_END_TIME_NAME,
                        'dataType' => Text::NAME,
                        'additionalClasses' => 'appointment_end_time',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    protected function getPriceFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Price'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_PRICE_NAME,
                        'dataType' => Number::NAME,
                        'addbefore' => $this->getCurrencySymbol(),
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Price Type" field
     *
     * @param int $sortOrder
     * @param array $config
     * @return array
     */
    protected function getPriceTypeFieldConfig($sortOrder, array $config = [])
    {
        return array_replace_recursive(
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Price Type'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_PRICE_TYPE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'options' => $this->productOptionsPrice->toOptionArray(),
                        ],
                    ],
                ],
            ],
            $config
        );
    }

    /**
     * Get config for "SKU" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getSkuFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('SKU'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_SKU_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Max Characters" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getMaxCharactersFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Max Characters'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_MAX_CHARACTERS_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "File Extension" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getFileExtensionFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Compatible File Extensions'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_FILE_EXTENSION_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Maximum Image Width" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getImageSizeXFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Maximum Image Size'),
                        'notice' => __('Please leave blank if it is not an image.'),
                        'addafter' => __('px.'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_IMAGE_SIZE_X_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Maximum Image Height" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getImageSizeYFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => ' ',
                        'addafter' => __('px.'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_IMAGE_SIZE_Y_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get options for drop-down control with product option types
     *
     * @return array
     */
    protected function getProductOptionTypes()
    {
        $options = [];
        $groupIndex = 0;

        $options[0] = [
                'value' => '0',
                'label'=> 'Non working rule',
                'optgroup' => [
                        '0' => [
                                'value' =>'date',
                                'label' => 'Date'
                            ],

                        '1' => [
                                'value' => 'days',
                                'label' => 'Days',
                            ]

                    ]
            ];
        return $options;
    }

    protected function getNonWorkingDayType($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('-- Please select --'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'dataScope' => static::FIELD_DAYS_TYPE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'options' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    protected function getCurrencySymbol()
    {
        return $this->storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();
    }

    /**
     * The getter function to get the locale currency for real application code
     *
     * @return \Magento\Framework\Locale\CurrencyInterface
     *
     * @deprecated
     */
    private function getLocaleCurrency()
    {
        if ($this->localeCurrency === null) {
            $this->localeCurrency = \Magento\Framework\App\ObjectManager::getInstance()->get(CurrencyInterface::class);
        }
        return $this->localeCurrency;
    }
    
    /**
     * Format price according to the locale of the currency
     *
     * @param mixed $value
     * @return string
     */
    protected function formatPrice($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        $store = $this->storeManager->getStore();
        $currency = $this->getLocaleCurrency()->getCurrency($store->getBaseCurrencyCode());
        $value = $currency->toCurrency($value, ['display' => \Magento\Framework\Currency::NO_SYMBOL]);

        return $value;
    }
}
