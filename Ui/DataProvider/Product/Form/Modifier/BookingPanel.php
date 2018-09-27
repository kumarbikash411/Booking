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

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Modal;
use Magento\Framework\UrlInterface;
use Magento\Framework\Phrase;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Catalog\Api\Data\ProductAttributeInterface;

/**
 * Data provider for Booking panel
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BookingPanel extends AbstractModifier
{
    const BOOKING_MODAL = 'booking';
    const DATA_SCOPE_BOOKING = 'booking';
    const GROUP_BOOKING = 'booking';
    public $providerPrefix = '_facilities_listing';

    /**
     * @var string
     */
    protected $scopePrefix;

    protected $scopeName;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    protected $formName;

    protected $associatedListingPrefix;

    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        $formName,
        $dataScopeName,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        ArrayManager $arrayManager,
        $scopePrefix = '',
        $associatedListingPrefix = '',
        $scopeName = ''

    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->formName = $formName;
        $this->dataScopeName = $dataScopeName;
        $this->scopePrefix = $scopePrefix;
        $this->_objectManager = $objectManager;
        $this->associatedListingPrefix = $associatedListingPrefix;
        $this->scopeName = $scopeName;
        $this->arrayManager = $arrayManager;
    }


    public function modifyMeta(array $meta)
    {

        if ($this->locator->getProduct()->getTypeId() != 'booking') {
            return $meta;
        }

        $attribute_set_name = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load($this->locator->getProduct()->getAttributeSetId())->getAttributeSetName();

        if ($attribute_set_name == 'Hotel Booking') {

            $meta[static::BOOKING_MODAL] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Booking Panel'),
                            'collapsible' => true,
                            'opened' => true,
                            'componentType' => Form\Fieldset::NAME,
                            'sortOrder' => 200,
                        ],
                    ],
                ],

                'children' => [

                    'booking' => $this->getFacilityGrid($attribute_set_name),
                    'room_button' => $this->getButtonSet(),


                ]
            ];
        } elseif ($attribute_set_name == 'Daily Rent Booking' || $attribute_set_name == 'Hourly Rent Booking') {

            $meta[static::BOOKING_MODAL] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Booking Panel'),
                            'collapsible' => true,
                            'opened' => true,
                            'componentType' => Form\Fieldset::NAME,
                            'sortOrder' => 200,
                        ],
                    ],
                ],

                'children' => [

                    'booking' => $this->getFacilityGrid($attribute_set_name),
                ]
            ];
        }

        return $meta;
    }

    protected function getButtonSet()
    {

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'component' => 'Ced_Booking/js/components/add_rooms_button',
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content1' => __(
                            'Add Rooms To Hotel.'
                        ),
                        'additionalClasses' => 'add-new-room',
                        'template' => 'ui/form/components/complex',
                        'createaddroomsbutton' => 'ns = ${ $.ns }, index = create_add_rooms_button',
                    ],
                ],
            ],
            'children' => [
                'create_add_rooms_button' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' =>
                                            $this->dataScopeName . '.bookingModal',
                                        'actionName' => 'trigger',
                                        'params' => ['active', true],

                                    ],
                                    [
                                        'targetName' =>
                                            $this->dataScopeName . '.bookingModal',
                                        'actionName' => 'openModal',
                                    ],
                                ],
                                'title' => __('Add Rooms'),
                                'sortOrder' => 1,
                            ],
                        ],
                    ],
                ],
                $this->getGrid(),
            ],
        ];
    }


    /**
     * Returns dynamic rows configuration
     *
     * @return array
     */
    protected function getGrid()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'label' => __('Added Rooms List'),
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Ced_Booking/js/components/dynamic-rows-booking-hotel',
                        'addButton' => false,
                        'isEmpty' => true,
                        'additionalClasses' => 'hotel-rooms-grid',
                        'itemTemplate' => 'record',
                        'dataScope' => 'data',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProviderChangeFromGrid' => 'change_product',
                        'dataProviderFromWizard' => 'variations',
                        'map' => [
                            'id' => 'id',
                            'type' => 'type',
                            'category' => 'category',
                            'price' => 'price',
                            'status' => 'status',
                            'facilitisIds' => 'facilitiesIds',
                            'roomNumbers' => 'roomNumbers',
                            'deletedRoomNumbers' => 'deletedRoomNumbers',
                            'excludeDaysCheckIn' => 'excludeDaysCheckIn',
                            'excludeDaysCheckOut' => 'excludeDaysCheckOut',
                            'deletedRoomExcludeDays' => 'deletedRoomExcludeDays',
                            'images' => 'images',
                            'min_booking_allowed_days' => 'min_booking_allowed_days',
                            'max_booking_allowed_days' => 'max_booking_allowed_days',
                            'description' => 'description'
                        ],
                        'links' => [
                            'insertDataFromGrid' => '${$.provider}:${$.dataProviderFromGrid}',
                            'insertDataFromWizard' => '${$.provider}:${$.dataProviderFromWizard}',
                            'changeDataFromGrid' => '${$.provider}:${$.dataProviderChangeFromGrid}',
                        ],
                        'sortOrder' => 20,
                        'columnsHeader' => true,
                        'columnsHeaderAfterRender' => false,
                    ],
                ],
            ],
            'children' => $this->getRows(),
        ];
    }

    /**
     * Returns Dynamic rows records configuration
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getRows()
    {
        return [
            'record' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Container::NAME,
                            'isTemplate' => true,
                            'is_collection' => true,
                            'component' => 'Magento_Ui/js/dynamic-rows/record',
                            'dataScope' => '',
                        ],
                    ],
                ],
                'children' => [
                    'id_container' => $this->getColumn('id', __('Id')),
                    'type_container' => $this->getColumn('type', __('Room Title')),
                    'category_container' => $this->getColumn('category', __('Category')),
                    'price_container' => $this->getColumn(
                        'price',
                        __('Price'),
                        [
                            'imports' => ['addbefore' => '${$.provider}:${$.parentScope}.price_currency'],
                            'validation' => ['validate-zero-or-greater' => true]
                        ],
                        ['dataScope' => 'price_string']
                    ),
                    'min_booking_allowed_days_container' => $this->getColumn('min_booking_allowed_days', __('Minimun Allowed Days')),
                    'max_booking_allowed_days_container' => $this->getColumn('max_booking_allowed_days', __('Maximum Allowed Days')),
                    'status' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'componentType' => 'text',
                                    'component' => 'Magento_Ui/js/form/element/abstract',
                                    'template' => 'Magento_ConfigurableProduct/components/cell-status',
                                    'label' => __('Status'),
                                    'dataScope' => 'status',
                                ],
                            ],
                        ],
                    ],
                    'description' => $this->getColumn('description', __('Description')),
                    'actionsList' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'additionalClasses' => 'data-grid-actions-cell',
                                    'componentType' => 'text',
                                    'component' => 'Magento_Ui/js/form/element/abstract',
                                    'template' => 'Ced_Booking/components/actions-list',
                                    'label' => __('Actions'),
                                    'fit' => true,
                                    'dataScope' => 'status',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get configuration of column
     *
     * @param string $name
     * @param \Magento\Framework\Phrase $label
     * @param array $editConfig
     * @param array $textConfig
     * @return array
     */
    protected function getColumn(
        $name,
        \Magento\Framework\Phrase $label,
        $textConfig = []
    ) {
        $fieldText['arguments']['data']['config'] = [
            'componentType' => Form\Field::NAME,
            'formElement' => Form\Element\Input::NAME,
            'elementTmpl' => 'Magento_ConfigurableProduct/components/cell-html',
            'dataType' => Form\Element\DataType\Text::NAME,
            'dataScope' => $name,
            'visibleIfCanEdit' => false,
            'imports' => [
                'visible' => '!${$.provider}:${$.parentScope}.canEdit'
            ],
        ];
        $fieldText['arguments']['data']['config'] = array_replace_recursive(
            $fieldText['arguments']['data']['config'],
            $textConfig
        );
        $container['arguments']['data']['config'] = [
            'componentType' => Container::NAME,
            'formElement' => Container::NAME,
            'component' => 'Magento_Ui/js/form/components/group',
            'label' => $label,
            'dataScope' => '',
        ];
        $container['children'] = [
            $name . '_text' => $fieldText,
        ];

        return $container;
    }


    protected function getFacilityGrid($attribute_set_name)
    {
        $content = __(
            'Assign Facilities To The Product.'
        );


        return [
            'children' => [
                'button_set' => $this->getFacilityButtonSet(
                    $content,
                    __('Assign Facilities'),
                    $this->scopePrefix . static::DATA_SCOPE_BOOKING,
                    $attribute_set_name
                ),
                'modal' => $this->getGenericModal(
                    __('Assign Facilities'),
                    $this->scopePrefix . static::DATA_SCOPE_BOOKING,
                    $attribute_set_name
                ),
                static::DATA_SCOPE_BOOKING => $this->getSelectedFacilityGrid($this->scopePrefix . static::DATA_SCOPE_BOOKING,$attribute_set_name),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Assign Facilities'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ]
        ];

    }


    protected function getFacilityButtonSet(Phrase $content, Phrase $buttonTitle, $scope,$attribute_set_name)
    {

        $modalTarget = $this->scopeName . '.' . static::GROUP_BOOKING . '.' . $scope . '.modal';

        if ($attribute_set_name == 'Hotel Booking' || $attribute_set_name == 'Default') {

            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'label' => false,
                            'content' => $content,
                            'template' => 'ui/form/components/complex',
                        ],
                    ],
                ],
                'children' => [
                    'button_' . $scope => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement' => 'container',
                                    'componentType' => 'container',
                                    'component' => 'Magento_Ui/js/form/components/button',
                                    'actions' => [
                                        [
                                            'targetName' => $modalTarget,
                                            'actionName' => 'toggleModal',
                                        ],
                                        [
                                            'targetName' => $modalTarget . '.' . $scope . '_facilities_listing',
                                            'actionName' => 'render',
                                        ]
                                    ],
                                    'title' => $buttonTitle,
                                    'provider' => null,
                                ],
                            ],
                        ],

                    ],
                ],
            ];
        } else if ($attribute_set_name == 'Daily Rent Booking' || $attribute_set_name == 'Hourly Rent Booking') {



            return [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'label' => false,
                            'content' => $content,
                            'template' => 'ui/form/components/complex',
                        ],
                    ],
                ],
                'children' => [
                    'button_' . $scope => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement' => 'container',
                                    'componentType' => 'container',
                                    'component' => 'Magento_Ui/js/form/components/button',
                                    'actions' => [
                                        [
                                            'targetName' => $modalTarget,
                                            'actionName' => 'toggleModal',
                                        ],
                                        [
                                            'targetName' => $modalTarget . '.' . $scope . '_rent_facilities_listing',
                                            'actionName' => 'render',
                                        ]
                                    ],
                                    'title' => $buttonTitle,
                                    'provider' => null,
                                ],
                            ],
                        ],

                    ],
                ],
            ];

        }


    }


    /**
     * Prepares config for modal slide-out panel
     *
     * @param Phrase $title
     * @param string $scope
     * @return array
     */
    protected function getGenericModal(Phrase $title, $scope,$attribute_set_name)
    {
        if ($attribute_set_name == 'Daily Rent Booking' || $attribute_set_name == 'Hourly Rent Booking') {

            $listingTarget = $scope . '_rent_facilities_listing';


            $modal = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Modal::NAME,
                            'dataScope' => '',
                            'options' => [
                                'title' => $title,
                                'buttons' => [
                                    [
                                        'text' => __('Cancel'),
                                        'actions' => [
                                            'closeModal'
                                        ]
                                    ],
                                    [
                                        'text' => __('Add Selected Facilities'),
                                        'class' => 'action-primary',
                                        'actions' => [
                                            [
                                                'targetName' => 'index = ' . $listingTarget,
                                                'actionName' => 'save'
                                            ],
                                            'closeModal'
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'children' => [
                    $listingTarget => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'autoRender' => false,
                                    'componentType' => 'insertListing',
                                    'dataScope' => $listingTarget,
                                    'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                                    'selectionsProvider' => $listingTarget . '.' . $listingTarget . '.booking_rent_facilities_listing_columns.ids',
                                    'ns' => $listingTarget,
                                    'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                    'realTimeLink' => true,
                                    'dataLinks' => [
                                        'imports' => false,
                                        'exports' => true
                                    ],
                                    'behaviourType' => 'simple',
                                    'externalFilterMode' => true,
                                    'imports' => [
                                        'productId' => '${ $.provider }:data.product.current_product_id',
                                        'storeId' => '${ $.provider }:data.product.current_store_id',
                                    ],
                                    'exports' => [
                                        'productId' => '${ $.externalProvider }:params.current_product_id',
                                        'storeId' => '${ $.externalProvider }:params.current_store_id',
                                    ]

                                ],
                            ],
                        ],
                    ],
                ],
            ];
        } elseif ($attribute_set_name == 'Hotel Booking' || $attribute_set_name == 'Default') {

            $listingTarget = $scope . '_facilities_listing';

            $modal = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Modal::NAME,
                            'dataScope' => '',
                            'options' => [
                                'title' => $title,
                                'buttons' => [
                                    [
                                        'text' => __('Cancel'),
                                        'actions' => [
                                            'closeModal'
                                        ]
                                    ],
                                    [
                                        'text' => __('Add Selected Facilities'),
                                        'class' => 'action-primary',
                                        'actions' => [
                                            [
                                                'targetName' => 'index = ' . $listingTarget,
                                                'actionName' => 'save'
                                            ],
                                            'closeModal'
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'children' => [
                    $listingTarget => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'autoRender' => false,
                                    'componentType' => 'insertListing',
                                    'dataScope' => $listingTarget,
                                    'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                                    'selectionsProvider' => $listingTarget . '.' . $listingTarget . '.booking_facilities_listing_columns.ids',
                                    'ns' => $listingTarget,
                                    'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                    'realTimeLink' => true,
                                    'dataLinks' => [
                                        'imports' => false,
                                        'exports' => true
                                    ],
                                    'behaviourType' => 'simple',
                                    'externalFilterMode' => true,
                                    'imports' => [
                                        'productId' => '${ $.provider }:data.product.current_product_id',
                                        'storeId' => '${ $.provider }:data.product.current_store_id',
                                    ],
                                    'exports' => [
                                        'productId' => '${ $.externalProvider }:params.current_product_id',
                                        'storeId' => '${ $.externalProvider }:params.current_store_id',
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $modal;
    }

    protected function getSelectedFacilityGrid($scope,$attribute_set_name)
    {

        if ($attribute_set_name == 'Daily Rent Booking' || $attribute_set_name == 'Hourly Rent Booking') {

            $dataProvider = $scope . '_rent_facilities_listing';
        } else if ($attribute_set_name == 'Hotel Booking' || $attribute_set_name == 'Default') {
            $dataProvider = $scope . '_facilities_listing';
        }

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'label' => null,
                        'columnsHeader' => true,
                        'columnsHeaderAfterRender' => false,
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Ced_Booking/js/dynamic-rows/dynamic-rows-grid',
                        'addButton' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => 'data.links',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProvider' => $dataProvider,
                        'map' => [
                            'id' => 'id',
                            'title' => 'title',
                            'description' => 'description'
                        ],
                        'links' => [
                            'insertData' => '${ $.provider }:${ $.dataProvider }'
                        ],
                        'sortOrder' => 2,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $this->fillMeta(),
                ],
            ],
        ];


    }


    /**
     * Retrieve meta column
     *
     * @return array
     */
    protected function fillMeta()
    {

        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 10),
            'title_container' => $this->getTextColumn('title',false, __('Title'),20),
            'desc_container' => $this->getTextColumn('description',false, __('Description'),30),
            'actionDelete' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'data-grid-actions-cell',
                            'componentType' => 'actionDelete',
                            'dataType' => Text::NAME,
                            'label' => __('Actions'),
                            'sortOrder' => 70,
                            'fit' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getTextColumn($dataScope, $fit, Phrase $label, $sortOrder)
    {
        $column = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'dataType' => Text::NAME,
                        'dataScope' => $dataScope,
                        'fit' => $fit,
                        'label' => $label,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],

        ];

        return $column;
    }


    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $amenityIds = [];
        $images = [];
        $product = $this->locator->getProduct();
        $productId = $product->getId();
        if (!$productId) {
            return $data;
        }
        if ($product->getTypeId() != 'booking') {
            return $data;
        }

        $data[$productId]['links']['booking'] = [];
        $data[$productId][1] =[];
        $BookingAmenitiesRelationModel = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('product_id',$productId);
        foreach ($BookingAmenitiesRelationModel as $amenity) {

            $amenities[] = $this->_objectManager->create('Ced\Booking\Model\Facilities')->load($amenity->getFacilityId())->getData();
        }

        if (isset($amenities) && count($amenities) != 0) {

            foreach ($amenities as $amenityData) {
                $data[$productId]['links']['booking'][] =  ['id' => $amenityData['id'],
                    'title' => $amenityData['title'],
                    'description' =>$amenityData['description']];
            }
        }

        $RoomsModel = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('product_id',$productId);
        if (count($RoomsModel) != 0) {
            foreach ($RoomsModel as $rooms) {

                $roomId = $rooms->getId();

                $RoomAmenitiesModel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->getCollection()->addFieldToFilter('room_id',$roomId);
                foreach ($RoomAmenitiesModel as $amenities) {
                    $amenityIds[] = $amenities->getAmenityId();
                }

                $RoomImagesModel = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation')->getCollection()->addFieldToFilter('room_id',$roomId);
                foreach ($RoomImagesModel as $image) {
                    $images[]['name'] = $image->getImageName();
                }


                $categoryModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($rooms->getRoomCategoryId());

                $roomTypeModel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($rooms->getRoomTypeId());

                $data[$productId][0][] = ['id'=>$roomId,
                    'type'=>$roomTypeModel->getTitle(),
                    'category' => $categoryModel->getTitle(),
                    'price'=>$rooms->getPrice(),
                    'status'=>$rooms->getStatus(),
                    'facilitiesIds'=>$amenityIds,
                    'roomNumbers' => [],
                    'deletedRoomNumbers' => [],
                    'excludeDaysCheckIn' => [],
                    'excludeDaysCheckOut' => [],
                    'deletedRoomExcludeDays' => [],
                    'images'=>$images,
                    'min_booking_allowed_days'=>$rooms->getMinBookingAllowedDays(),
                    'max_booking_allowed_days'=>$rooms->getMaxBookingAllowedDays(),
                    'description'=>$rooms->getDescription()];
            }
        }
        return $data;
    }
}