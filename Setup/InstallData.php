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
namespace Ced\Booking\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\ObjectManagerInterface;
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface

{

	private $bookingSetupFactory;

    const ENTITY_TYPE = \Magento\Catalog\Model\Product::ENTITY;

	/**
	 *
	 * Init
	 *
	 */
	public function __construct(
        EavSetupFactory $eavSetupFactory,
        ObjectManagerInterface $objectManager,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute)

	{
		$this->eavSetupFactory = $eavSetupFactory;
        $this->_objectManager = $objectManager;
        $this->_eavAttribute = $eavAttribute;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{

		$setup->startSetup ();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $defaultId = $eavSetup->getDefaultAttributeSetId(self::ENTITY_TYPE);

        $entityTypeId = $eavSetup->getEntityTypeId(self::ENTITY_TYPE);

        $groupName = 'Booking General Information';

        $eavSetup->addAttributeGroup($entityTypeId,$defaultId,$groupName,12);

        $model = $this->_objectManager
        ->create('Magento\Eav\Api\Data\AttributeSetInterface')
        ->setEntityTypeId(4)
        ->setAttributeSetName('Hotel Booking');

        $this->_objectManager
        ->create('Magento\Eav\Api\AttributeSetManagementInterface')
        ->create(self::ENTITY_TYPE, $model, $defaultId)
        ->save();

        $eavSetup->addAttribute('catalog_product', 'booking_policy', [
                'group'            => '',
                'note'             => '',
                'input'            => 'textarea',
                'type'             => 'text',
                'label'            => 'Booking Policy',
                'backend'          => '',
                'visible'          => true,
                'required'         => false,
                //'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'sort_order'       => 110,
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
         $eavSetup->addAttribute('catalog_product', 'booking_terms_and_condition', [
                'group'            => '',
                'note'             => '',
                'input'            => 'textarea',
                'type'             => 'text',
                'label'            => 'Booking Terms and Conditions',
                'backend'          => '',
                'visible'          => true,
                'required'         => false,
               // 'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'sort_order'       => 120,
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );


        $attributesetData = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load('Hotel Booking','attribute_set_name');

        $attributesetId = $attributesetData->getAttributeSetId();

        $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'booking_policy');
        $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'booking_terms_and_condition');
        $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'booking_fax');

        $model = $this->_objectManager
        ->create('Magento\Eav\Api\Data\AttributeSetInterface')
        ->setEntityTypeId(4)
        ->setAttributeSetName('Daily Rent Booking');

        $this->_objectManager
        ->create('Magento\Eav\Api\AttributeSetManagementInterface')
        ->create(self::ENTITY_TYPE, $model, $attributesetId)
        ->save();

        $eavSetup->addAttribute('catalog_product', 'booking_service_start', [
                'group'            => '',
                'note'             => '[Example: 12:00 am]',
                'input'            => 'text',
                'type'             => 'varchar',
                'label'            => 'Booking Service Start',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 5,
                'user_defined'     => 1,
                'default'          => '12:00 am',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'booking_service_end', [
                'group'            => '',
                'note'             => '[Example: 12:00 am]',
                'input'            => 'text',
                'type'             => 'varchar',
                'label'            => 'Booking Service End',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 10,
                'user_defined'     => 1,
                'default'          => '01:00 am',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                     ]
             );
            $eavSetup->addAttribute('catalog_product', 'booking_min_days', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'varchar',
                'label'            => 'Booking Minimun Days',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 130,
                'user_defined'     => 1,
                'default'          => '1',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                     ]
             );
            $eavSetup->addAttribute('catalog_product', 'booking_max_days', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'varchar',
                'label'            => 'Booking Maximum Days',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 140,
                'user_defined'     => 1,
                'default'          => '100',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                     ]
             );

            $eavSetup->addAttribute('catalog_product', 'stock_qty_for_a_day', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Stock qty for a day',
                'backend'          => '',
                'frontend_class'   => 'validate-number',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 170,
                'user_defined'     => 1,
                'default'          => '10',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                     ]
            );

            $eavSetup->addAttribute(
            'catalog_product',
            'non_working_days',
             [
                'group'            => '',
                'note'             => '',
                'type'             => 'text',
                'label'            => 'Non Working Days',
                'backend'          => '',
                'visible'          => false,
                'required'         => false,
                'sort_order'       => 290,
                'user_defined'     => false,
                'source'           => '',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            ]
        );


            $rentattributeData = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load('Daily Rent Booking','attribute_set_name');

            $rentattributesetId = $rentattributeData->getAttributeSetId();

            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'booking_service_start');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'booking_service_end');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'booking_min_days');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'booking_max_days');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'stock_qty_for_a_day');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'non_working_days');

            $model = $this->_objectManager
            ->create('Magento\Eav\Api\Data\AttributeSetInterface')
            ->setEntityTypeId(4)
            ->setAttributeSetName('Hourly Rent Booking');

            $this->_objectManager
            ->create('Magento\Eav\Api\AttributeSetManagementInterface')
            ->create(self::ENTITY_TYPE, $model, $attributesetId)
            ->save();

            $eavSetup->addAttribute('catalog_product', 'service_start_time', [
                'group'            => '',
                'note'             => '[Example: 12:00 am]',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Service Start Time',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 150,
                'default'          => '12:00 am',
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'service_end_time', [
                'group'            => '',
                'note'             => '[Example: 01:00 am]',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Service End Time',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 160,
                'user_defined'     => 1,
                'default'          => '01:00 am',
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'min_booking_hours', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Min Booking Hours',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 180,
                'default'          => '1',
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'max_booking_hours', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Max Booking Hours',
                'backend'          => '',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 190,
                'default'          => '100',
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\Booking',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
             $eavSetup->addAttribute('catalog_product', 'stock_qty_for_a_interval', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Stock qty for a interval',
                'backend'          => '',
                'frontend_class'   => 'validate-number',
                'visible'          => true,
                'required'         => true,
                'sort_order'       => 200,
                'default'          => '10',
                'user_defined'     => 1,
                'source'           => '',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );

            $renthourlyattributeData = $this->_objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load('Hourly Rent Booking','attribute_set_name');

            $renthourlyattributesetId = $renthourlyattributeData->getAttributeSetId();

            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'service_start_time');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'service_end_time');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'min_booking_hours');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'max_booking_hours');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'stock_qty_for_a_interval');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'non_working_days');

             $eavSetup->addAttribute('catalog_product', 'booking_check_in_time', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'Start Time',
                'backend'          => '',
                'visible'          => true,
                'sort_order'       => 90,
                'user_defined'     => 1,
                'default'          => '12:00 am',
                'source'           => '',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'booking_check_out_time', [
                'group'            => '',
                'note'             => '',
                'input'            => 'text',
                'type'             => 'text',
                'label'            => 'End Time',
                'backend'          => '',
                'visible'          => true,
                'sort_order'       => 100,
                'default'          => '01:00 am',
                'user_defined'     => 1,
                'source'           => '',
                'comparable'       => 0,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
                );
            $eavSetup->addAttribute('catalog_product', 'star_rating', [
                'group'            => '',
                'note'             => '',
                'input'            => 'select',
                'type'             => 'text',
                'label'            => 'Star Rating',
                'backend'          => '',
                'required'         => false,
                'sort_order'       => 240,
                'user_defined'     => 1,
                'source'           => 'Ced\Booking\Model\HotelStarRating',
                'comparable'       => 0,
                'visible'          => true,
                'visible_on_front' => 0,
                'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    ]
            );
            $eavSetup->addAttribute('catalog_product', 'address', [
                    'group'            => '',
                    'note'             => '',
                    'input'            => 'select',
                    'type'             => 'text',
                    'label'            => 'Address',
                    'backend'          => '',
                    'required'         => true,
                    'sort_order'       => 500,
                    'user_defined'     => 1,
                    'default'          => 'US',
                    'source'           => 'Ced\Booking\Model\BookingAddress',
                    'comparable'       => 0,
                    'visible'          => true,
                    'visible_on_front' => 0,
                    'global'           => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
            $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'address');
            $eavSetup->addAttributeToSet($entityTypeId, $rentattributesetId, $groupName, 'address');
            $eavSetup->addAttributeToSet($entityTypeId, $renthourlyattributesetId, $groupName, 'address');
            $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'booking_check_in_time');
            $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'booking_check_out_time');
            $eavSetup->addAttributeToSet($entityTypeId, $attributesetId, $groupName, 'star_rating');


            $fieldList = [
                'price',
                'special_price',
                'special_from_date',
                'special_to_date',
                'minimal_price',
                'cost',
                'tier_price',
            ];

            // make these attributes applicable to downloadable products
            foreach ($fieldList as $field) {
                $applyTo = explode(
                    ',',
                    $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
                );
                if (!in_array('booking', $applyTo)) {
                    $applyTo[] = 'booking';
                    $eavSetup->updateAttribute(
                        \Magento\Catalog\Model\Product::ENTITY,
                        $field,
                        'apply_to',
                        implode(',', $applyTo)
                    );
                }
            }

            $sampleAmenities = [0=> ['type'=>'hotel',
                                    'status'=>1,
                                    'title'=>'Swimming Pool',
                                    'image_type'=> 'fontawesome',
                                    'image'=> 'im-pool',
                                    'desc'=>'Outdoor pools may be open seasonally in temperate climates. Indoor pools can be open year round in any climate.'],
                                1=> ['type'=>'hotel',
                                    'status'=>1,
                                    'title'=>'Parking',
                                    'image_type'=> 'fontawesome',
                                    'image'=> 'fa-taxi',
                                    'desc'=>''
                                    ],
                                2=> ['type'=>'room',
                                     'status'=>1,
                                     'title'=>'Television',
                                     'image_type'=> 'fontawesome',
                                     'image'=> 'fa-desktop',
                                     'desc'=>''
                                    ],
                                3=> ['type'=>'room',
                                     'status'=>1,
                                     'title'=>'Bed',
                                     'image_type'=> 'fontawesome',
                                     'image'=> 'fa-bed',
                                     'desc'=>''
                                    ],
                                4=> ['type'=>'rent',
                                     'status'=>1,
                                     'title'=>'wi-fi',
                                     'image_type'=> 'fontawesome',
                                     'image'=> 'fa-wifi',
                                     'desc'=>''
                                    ],
                                5=> ['type'=>'rent',
                                     'status'=>1,
                                     'title'=>'Desktop',
                                     'image_type'=> 'fontawesome',
                                     'image'=> 'fa-desktop',
                                     'desc'=>''
                                    ]
                               ];

            foreach ($sampleAmenities as $amenities) {

                $amenitiesModel = $this->_objectManager->create('Ced\Booking\Model\Facilities');
                $amenitiesModel->setData('status',$amenities['status'])
                                ->setData('title',$amenities['title'])
                                ->setData('type',$amenities['type'])
                                ->setData('image_type',$amenities['image_type'])
                                 ->setData('image',$amenities['image'])
                                ->setData('desc',$amenities['desc'])
                                ->save();
            }

		$setup->endSetup ();
	}
}

