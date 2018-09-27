<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * You can check the licence at this URL: http://cedcommerce.com/license-agreement.txt
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   ced_booking
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt  
 */

namespace Ced\Booking\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {
    /**
     *
     * {@inheritdoc} @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup ();

        /**
         * Create table 'ced_booking_rooms'
         */
        $rooms = $installer->getTable ( 'ced_booking_room' );
        if ($installer->getConnection ()->isTableExists ( $rooms ) != true) {
            $tableRooms = $installer->getConnection ()->newTable ( $rooms )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Room Id' )->addColumn ( 'product_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Product Id' )->addColumn ( 'room_category_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Room Category Id' )->addColumn ( 'room_type_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Room Type Id' )->addColumn ( 'status', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Status' )->addColumn ( 'price', Table::TYPE_DECIMAL,'10,2',[],
                'Price' )->addColumn ( 'min_booking_allowed_days', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Min Booking Allowed Days' )->addColumn ( 'max_booking_allowed_days', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Max Booking Allowed Days' )->addColumn ( 'description', Table::TYPE_TEXT, null, [
                'nullable' => false
            ], 'Description' )->setComment ( 'Rooms Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRooms );
        }


        /**
         * Create table 'ced_booking_amenities'
         */
        $facilities = $installer->getTable ( 'ced_booking_amenities' );
        if ($installer->getConnection ()->isTableExists ( $facilities ) != true) {
            $tableFacilities = $installer->getConnection ()->newTable ( $facilities )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Amenity Id' )->addColumn ( 'type', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'amenity type' )->addColumn ( 'booking_type', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Booking type' )->addColumn ( 'status', Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Status' )
                ->addColumn ( 'title', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Title' )
                ->addColumn ( 'image_type', Table::TYPE_TEXT, 255, [
                    'nullable' => false
                ], 'Image Type' )
                ->addColumn ( 'image', Table::TYPE_TEXT, 255, [
                'nullable' => true
            ], 'Image' )->addColumn ( 'description', Table::TYPE_TEXT, null, [
                'nullable' => true,
                'default' => ''
            ], 'amenity desc' )->setComment ( 'Amenities Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableFacilities );
        }

        /**
         * Create table 'ced_booking_room_amenities'
         */
        $Roomfacilities = $installer->getTable ( 'ced_booking_room_amenities' );
        if ($installer->getConnection ()->isTableExists ( $Roomfacilities ) != true) {
            $tableRoomFacilities = $installer->getConnection ()->newTable ( $Roomfacilities )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Amenity Id' )->addColumn ( 'room_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,

            ], 'Room Id' )->addColumn ( 'amenity_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,
            ], 'Facility Id' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_amenities',
                    'room_id',
                    'ced_booking_room',
                    'id'
                ),
                'room_id',
                $installer->getTable('ced_booking_room'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_amenities',
                    'amenity_id',
                    'ced_booking_amenities',
                    'id'
                ),
                'amenity_id',
                $installer->getTable('ced_booking_amenities'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'Room Amenities Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomFacilities );
        }

        /**
         * Create table 'ced_booking_room_numbers'
         */
        $roomnumbers = $installer->getTable ( 'ced_booking_room_numbers' );
        if ($installer->getConnection ()->isTableExists ( $roomnumbers ) != true) {
            $tableRoomnumbers = $installer->getConnection ()->newTable ( $roomnumbers )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Amenity Id' )->addColumn ( 'room_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Room Id' )->addColumn ( 'product_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Product Id' )->addColumn ( 'room_numbers', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Sort Order' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_numbers',
                    'room_id',
                    'ced_booking_room',
                    'id'
                ),
                'room_id',
                $installer->getTable('ced_booking_room'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'Room Numbers Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomnumbers );
        }

        /* ced_booking_room_exclude_days */

        $roomnexcludedays = $installer->getTable ( 'ced_booking_room_exclude_days' );
        if ($installer->getConnection ()->isTableExists ( $roomnexcludedays ) != true) {
            $tableRoomexcludedays = $installer->getConnection ()->newTable ( $roomnexcludedays )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Amenity Id' )->addColumn ( 'room_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Room Id' )->addColumn ( 'day_start', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Day Start' )->addColumn ( 'day_end', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Day End' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_exclude_days',
                    'room_id',
                    'ced_booking_room',
                    'id'
                ),
                'room_id',
                $installer->getTable('ced_booking_room'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'Room Exclude Days Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomexcludedays );
        }



        /**
         * Create table 'ced_booking_room_types'
         */
        $roomtypes = $installer->getTable ( 'ced_booking_room_types' );
        if ($installer->getConnection ()->isTableExists ( $roomtypes ) != true) {
            $tableRoomtypes = $installer->getConnection ()->newTable ( $roomtypes )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'status', Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Status' )->addColumn ( 'title', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'title' )->addColumn ( 'max_allowed_child', Table::TYPE_INTEGER,null, [
                'nullable' => false,
                'default' => 0
            ], 'Max Allowed Child' )->addColumn ( 'min_allowed_child', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Min Allowed Child' )->setComment ( 'room type Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomtypes );
        }

        /**
         * Create table 'ced_booking_room_category'
         */
        $roomtypescategory = $installer->getTable ( 'ced_booking_room_category' );
        if ($installer->getConnection ()->isTableExists ( $roomtypescategory ) != true) {
            $tableRoomtypescategory = $installer->getConnection ()->newTable ( $roomtypescategory )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'title', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'category title' )->addColumn ( 'description', Table::TYPE_TEXT, null, [
                'nullable' => false,
                'default' => ''
            ], 'category description' )->setComment ( 'room category Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomtypescategory );
        }

        /**
         * Create table 'ced_booking_room_type_category_relation'
         */
        $roomtypescategoryrelation = $installer->getTable ( 'ced_booking_room_type_category_relation' );
        if ($installer->getConnection ()->isTableExists ( $roomtypescategoryrelation ) != true) {
            $tableRoomtypescategoryrelation = $installer->getConnection ()->newTable ( $roomtypescategoryrelation )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'room_type_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Room Type Id' )->addColumn ( 'room_category_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true
            ], 'Room Category Id' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_type_category_relation',
                    'room_type_id',
                    'ced_booking_room_types',
                    'id'
                ),
                'room_type_id',
                $installer->getTable('ced_booking_room_types'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_type_category_relation',
                    'room_category_id',
                    'ced_booking_room_category',
                    'id'
                ),
                'room_category_id',
                $installer->getTable('ced_booking_room_category'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'room category Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableRoomtypescategoryrelation );
        }

        /**
         * Create table 'ced_booking_room_image_relation'
         */
        $roomimages = $installer->getTable ( 'ced_booking_room_image_relation' );
        if ($installer->getConnection ()->isTableExists ( $roomimages ) != true) {
            $tableroomimages = $installer->getConnection ()->newTable ( $roomimages )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'room_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,
            ], 'Room Id' )->addColumn ( 'image_name', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Image name' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_room_image_relation',
                    'room_id',
                    'ced_booking_room',
                    'id'
                ),
                'room_id',
                $installer->getTable('ced_booking_room'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'room image relation Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableroomimages );
        }


        /*ced_booking_room_order*/

        $roomorder = $installer->getTable ( 'ced_booking_room_order' );
        if ($installer->getConnection ()->isTableExists ( $roomorder ) != true) {
            $tableroomorder = $installer->getConnection ()->newTable ( $roomorder )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'order_id', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Order Id' )->addColumn ( 'product_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Product Id' )->addColumn ( 'room_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,
            ], 'Room Id' )->addColumn ( 'room_number_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Room Number Id' )->addColumn ( 'booking_start_date', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Booking Start Date' )->addColumn ( 'booking_end_date', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Booking End Date' )->addColumn ( 'total_days', Table::TYPE_INTEGER, null, [
                'nullable' => false
            ], 'Total Days' )->addColumn ( 'status', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Status' )->setComment ( 'book room Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tableroomorder );
        }

        /*ced_booking_rent_order*/

        $rentorder = $installer->getTable ( 'ced_booking_rent_order' );
        if ($installer->getConnection ()->isTableExists ( $rentorder ) != true) {
            $tablerentorder = $installer->getConnection ()->newTable ( $rentorder )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'order_id', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Order Id' )->addColumn ( 'product_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Product Id' )->addColumn ( 'booking_start_date', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Booking Start Date' )->addColumn ( 'booking_end_date', Table::TYPE_DATETIME, null, [
                'nullable' => false
            ], 'Booking End Date' )->addColumn ( 'total_days', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Total Days' )->addColumn ( 'total_hours', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Total Hours' )->addColumn ( 'qty', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Qty' )->addColumn ( 'status', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Status' )->setComment ( 'book room Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tablerentorder );
        }

        /**
         * Create table 'ced_booking_amenities_relation'
         */
        $entfacilitiesrelation = $installer->getTable ( 'ced_booking_amenities_relation' );
        if ($installer->getConnection ()->isTableExists ( $entfacilitiesrelation ) != true) {
            $tablerentfacilities = $installer->getConnection ()->newTable ( $entfacilitiesrelation )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'product_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'default' => 0
            ], 'Product Id' )->addColumn ( 'facility_id', Table::TYPE_INTEGER, null, [
                'nullable' => false,
                'unsigned' => true,
            ], 'Facility Id' )->addForeignKey(
                $installer->getFkName(
                    'ced_booking_amenities_relation',
                    'facility_id',
                    'ced_booking_amenities',
                    'id'
                ),
                'facility_id',
                $installer->getTable('ced_booking_amenities'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment ( 'Rent amenities relation Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tablerentfacilities );
        }


        /*ced_booking_location*/

        $location = $installer->getTable ( 'ced_booking_location' );
        if ($installer->getConnection ()->isTableExists ( $location ) != true) {
            $tablelocation = $installer->getConnection ()->newTable ( $location )->addColumn ( 'id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ], 'Id' )->addColumn ( 'email', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Email' )->addColumn ( 'contact', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Contact' )->addColumn ( 'street_address', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Street Address' )->addColumn ( 'city', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'City' )->addColumn ( 'state', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'State' )->addColumn ( 'zip', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Zip' )->addColumn ( 'country', Table::TYPE_TEXT, 255, [
                'nullable' => false
            ], 'Country' )->setComment ( 'book room Table' )->setOption ( 'type', 'InnoDB' )->setOption ( 'charset', 'utf8' );
            $installer->getConnection ()->createTable ( $tablelocation );
        }

        $installer->endSetup ();
    }
}
