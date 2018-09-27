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
 * @author      CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\Booking\Block\Adminhtml\Product\Steps;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Media\Config;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ProductFactory;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Images extends \Magento\Ui\Block\Component\StepsWizard\StepAbstract
{
    /** @var Image */
    protected $image;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var Config
     */
    private $catalogProductMediaConfig;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * Filesystem facade
     *
     * @var \Magento\Framework\Filesystem
     *
     * @deprecated
     */
    protected $_filesystem;

    /**
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    protected $_mediaConfig;

    /**
     * @param Context $context
     * @param Image $image
     * @param Config $catalogProductMediaConfig
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Context $context,
        Image $image,
        Config $catalogProductMediaConfig,
        ProductFactory $productFactory,
        \Magento\Framework\objectManagerInterface $objectManager,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        LocatorInterface $locator
    ) {
        parent::__construct($context);
        $this->image = $image;
        $this->productFactory = $productFactory;
        $this->catalogProductMediaConfig = $catalogProductMediaConfig;
        $this->_objectManager = $objectManager;
        $this->locator = $locator;
        $this->_filesystem = $context->getFilesystem();;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_mediaConfig = $mediaConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getCaption()
    {
        return __('Room Images');
    }

    /**
     * @return string
     */
    public function getNoImageUrl()
    {
        return $this->image->getDefaultPlaceholderUrl('thumbnail');
    }

    /**
     * Get image types data
     *
     * @return array
     */
    public function getImageTypes()
    {
        $imageTypes = [];
        foreach ($this->catalogProductMediaConfig->getMediaAttributeCodes() as $attributeCode) {
            /* @var $attribute Attribute */
            $imageTypes[$attributeCode] = [
                'code' => $attributeCode,
                'value' => '',
                'label' => $attributeCode,
                'scope' => '',
                'name' => $attributeCode,
            ];
        }
        return $imageTypes;
    }

    /**
     * @return array
     */
    public function getMediaAttributes()
    {
        static $simple;
        if (empty($simple)) {
            $simple = $this->productFactory->create()->setTypeId(Type::TYPE_SIMPLE)->getMediaAttributes();
        }
        return $simple;
    }

    public function getProductId()
    {
        $params = $this->getRequest()->getParams();
        if(isset($params['id'])) {
            return $params['id'];
        } else {
            return null;
        }
    }

    public function getRoomId()
    {
        $param = $this->getRequest()->getParams();
        if(isset($param['Room_Id'])) {
            return $param['Room_Id'];
        } else {
            return null;
        }
    }

    public function getRoomsImages()
    {
        $roomId = $this->getRoomId();
        
        if ($roomId != null) {
            $RoomImageRelation = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation')->getCollection()->addFieldToFilter('room_id',$roomId);

            return $RoomImageRelation;
        } else {

            return false;
        }
    }
    public function getRooms()
    {
        $RoomsModel = $this->_objectManager->create('Ced\Booking\Model\Rooms')->getCollection()->addFieldToFilter('product_id',$this->getProductId());

        if (count($RoomsModel) != 0) {
            foreach ($RoomsModel as $rooms) {

                $categoryModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($rooms->getRoomCategoryId());

                $roomTypeModel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($rooms->getRoomTypeId());

                // $RoomAmenitiesModel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->getCollection()->addFieldToFilter('room_id',$rooms->getId());
                // foreach ($RoomAmenitiesModel as $amenities) {
                //     $amenityIds[] = $amenities->getAmenityId();
                // }

                // $RoomImagesModel = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation')->getCollection()->addFieldToFilter('room_id',$rooms->getId());
                // foreach ($RoomImagesModel as $image) {
                //     $images[]['name'] = $image->getImageName();
                // }

                $roomNumbersCollection = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->getCollection()->addFieldToFilter('room_id',$rooms->getId());                

                $roomsdata[] = ['id'=>$rooms->getId(),
                    'product_id'=>$rooms->getProductId(),
                    'type'=>$roomTypeModel->getTitle(),
                    'category' => $categoryModel->getTitle(),
                    'min_booking_allowed_days'=>$rooms->getMinBookingAllowedDays(),
                    'max_booking_allowed_days'=>$rooms->getMaxBookingAllowedDays(),
                    'price'=>$rooms->getPrice(),
                    'status'=>$rooms->getStatus(),
                    'description'=>$rooms->getDescription(),
                    'roomNumbers'=>$roomNumbersCollection->getData()
                    //'images'=>$images
                    ];
            }
            return json_encode($roomsdata);
        } else {

            return null;
        }       
    }

    public function getImagesJson()
    {
        $image = [];
        $MediaUrl = $this->_objectManager->create('\Magento\Store\Model\StoreManagerInterface')
                        ->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $images = $this->getRoomsImages();
        $roomId = $this->getRoomId();

        if ($roomId != null) {
          if (count($images)!=0) {

              foreach ($images as $val) {

                  $image['images'][$val->getData('id')] = ['disabled'=> "0",
                                        'disabled_default' => "0",
                                        'entity_id' => $val->getData('id'),
                                        'file' => $val->getImageName(),
                                        'label' => "",
                                        'label_default' => null,
                                        'media_type' => "image",
                                        'position' => $val->getData('id'),
                                        'position_default' => "1",
                                        'size' => 677440,
                                        'url' => $MediaUrl.'ced/booking/room/images/'.$val->getImageName(),
                                        'value_id' => $val->getData('id')
                  ];
              }
          }
        }
        

        $value = $image;

        
        if (is_array($value) &&
            array_key_exists('images', $value) &&
            is_array($value['images']) &&
            count($value['images'])
        ) {
            $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $images = $this->sortImagesByPosition($value['images']);
            foreach ($images as &$image) {
                $image['url'] = $image['url'];
                $image['size'] = $image['size'];
            }
            return $this->_jsonEncoder->encode($images);
        }
        return '[]';
    }

    /**
     * Sort images array by position key
     *
     * @param array $images
     * @return array
     */
    private function sortImagesByPosition($images)
    {
        if (is_array($images)) {
            usort($images, function ($imageA, $imageB) {
                return ($imageA['position'] < $imageB['position']) ? -1 : 1;
            });
        }
        return $images;
    }
}
