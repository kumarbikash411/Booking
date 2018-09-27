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

namespace Ced\Booking\Block\Adminhtml\Product\Edit\Tab\Variations\Config;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Matrix extends \Magento\Backend\Block\Template
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /** @var \Magento\Catalog\Helper\Image */
    protected $image;

    /** @var null|array */
    private $productMatrix;

    /** @var null|array */
    private $productAttributes;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Helper\Image $image
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param LocatorInterface $locator
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Helper\Image $image,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        LocatorInterface $locator,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->localeCurrency = $localeCurrency;
        $this->image = $image;
        $this->locator = $locator;
        $this->_objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->localeCurrency->getCurrency($this->_storeManager->getStore()->getBaseCurrencyCode())->getSymbol();
    }

    /**
     * Retrieve currently edited product object
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->locator->getProduct();
    }

    /**
     * Retrieve data source for variations data
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->getData('config/provider');
    }

    /**
     * Retrieve configurable modal name
     *
     * @return string
     */
    public function getModal()
    {
        return $this->getData('config/modal');
    }

    /**
     * Retrieve form name
     *
     * @return string
     */
    public function getForm()
    {
        return $this->getData('config/form');
    }

    /**
     * Retrieve configurable modal name
     *
     * @return string
     */
    public function getConfigurableModal()
    {
        return $this->getData('config/BookingModal');
    }

    /**
     * Get url for product edit
     *
     * @param string $id
     * @return string
     */
    public function getEditProductUrl($id)
    {
        return $this->getUrl('catalog/*/edit', ['id' => $id]);
    }

    /**
     * Get url to upload files
     *
     * @return string
     */
    public function getImageUploadUrl()
    {
        return $this->getUrl('catalog/product_gallery/upload');
    }

    /**
     * @param array $initData
     * @return string
     */
    public function getVariationWizard($initData)
    {
        /** @var \Magento\Ui\Block\Component\StepsWizard $wizardBlock */
        $wizardBlock = $this->getChildBlock($this->getData('config/nameStepWizard'));
        if ($wizardBlock) {
            $wizardBlock->setInitData($initData);
            return $wizardBlock->toHtml();
        }
        return '';
    }

    public function getSavedRoomsFacilities()
    {
        $param = $this->getRequest()->getParams();
        if(isset($param['Room_Id']) && $param['Room_Id']!='') {

            $RoomAmenitiesmodel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->getCollection()->addFieldToFilter('room_id',$param['Room_Id']);

            if (count($RoomAmenitiesmodel) != 0) {

                foreach ($RoomAmenitiesmodel as $amenities) {
                    $amenitiesmodel[] = $this->_objectManager->create('Ced\Booking\Model\Facilities')->load($amenities->getAmenityId())->getData();
                }
            } else {
                    $amenitiesmodel = '';
            }

            return $amenitiesmodel;
        } else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getProductMatrix()
    {
        if ($this->productMatrix === null) {
            $this->prepareVariations();
        }
        return $this->productMatrix;
    }

    /**
     * @return array|null
     */
    public function getProductAttributes()
    {
        if ($this->productAttributes === null) {
            $this->prepareVariations();
        }
        return $this->productAttributes;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return void
     * TODO: move to class
     */
    protected function prepareVariations()
    {
        $variations = $this->getVariations();
        $productMatrix = [];
        $attributes = [];
        if ($variations) {
            $usedProductAttributes = $this->getUsedAttributes();
            $productByUsedAttributes = $this->getAssociatedProducts();
            $configurableAttributes = $this->getAttributes();
            foreach ($variations as $variation) {
                $attributeValues = [];
                foreach ($usedProductAttributes as $attribute) {
                    $attributeValues[$attribute->getAttributeCode()] = $variation[$attribute->getId()]['value'];
                }
                $key = implode('-', $attributeValues);
                if (isset($productByUsedAttributes[$key])) {
                    $product = $productByUsedAttributes[$key];
                    $price = $product->getPrice();
                    $variationOptions = [];
                    foreach ($usedProductAttributes as $attribute) {
                        if (!isset($attributes[$attribute->getAttributeId()])) {
                            $attributes[$attribute->getAttributeId()] = [
                                'code' => $attribute->getAttributeCode(),
                                'label' => $attribute->getStoreLabel(),
                                'id' => $attribute->getAttributeId(),
                                'position' => $configurableAttributes[$attribute->getAttributeId()]['position'],
                                'chosen' => [],
                            ];
                            foreach ($attribute->getOptions() as $option) {
                                if (!empty($option->getValue())) {
                                    $attributes[$attribute->getAttributeId()]['options'][] = [
                                        'attribute_code' => $attribute->getAttributeCode(),
                                        'attribute_label' => $attribute->getStoreLabel(0),
                                        'id' => $option->getValue(),
                                        'label' => $option->getLabel(),
                                        'value' => $option->getValue(),
                                    ];
                                }
                            }
                        }
                        $optionId = $variation[$attribute->getId()]['value'];
                        $variationOption = [
                            'attribute_code' => $attribute->getAttributeCode(),
                            'attribute_label' => $attribute->getStoreLabel(0),
                            'id' => $optionId,
                            'label' => $variation[$attribute->getId()]['label'],
                            'value' => $optionId,
                        ];
                        $variationOptions[] = $variationOption;
                        $attributes[$attribute->getAttributeId()]['chosen'][] = $variationOption;
                    }

                    $productMatrix[] = [
                        'productId' => $product->getId(),
                        'images' => [
                            'preview' => $this->image->init($product, 'product_thumbnail_image')->getUrl()
                        ],
                        'sku' => $product->getSku(),
                        'name' => $product->getName(),
                        'quantity' => $this->getProductStockQty($product),
                        'price' => $price,
                        'options' => $variationOptions,
                        'weight' => $product->getWeight(),
                        'status' => $product->getStatus()
                    ];
                }
            }
        }
        $this->productMatrix = $productMatrix;
        $this->productAttributes = array_values($attributes);
    }
}
