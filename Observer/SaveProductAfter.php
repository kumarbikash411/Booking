<?php 
namespace Ced\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Magento\Framework;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use \Magento\Payment\Model\InfoInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;

class SaveProductAfter implements ObserverInterface
{
	/**
	 * @var ObjectManagerInterface
	 */
	protected $_objectManager;
	protected $model;
	protected $payment_data;
	/**
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	 function __construct(
			\Magento\Framework\ObjectManagerInterface $objectManager,
	 		RequestInterface $request,
			ScopeConfig $scopeConfig,
			\Magento\Framework\Registry $registry,
			\Magento\Customer\Model\Session $customerSession,
			Product $catelogproductmodel,
			\Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute,
			\Magento\Framework\Message\ManagerInterface $messageManager
	) {

		$this->_objectManager=$objectManager;
		$this->scopeConfig = $scopeConfig;
		$this->registry = $registry;
		$this->request = $request;
		$this->customerSession = $customerSession;
		$this->_catalogproduct = $catelogproductmodel;
		$this->eavAttribute = $eavAttribute;
		$this->messageManager = $messageManager;
	}

	/**
	 * customer register event handler
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{

		$product = $observer->getEvent()->getForm();
		$productdata = $observer->getEvent()->getProduct();

		$post = $this->request->getPost();		
		$productId = $this->_catalogproduct->getIdBySku($post['product']['sku']);
		$catalogModel =  $this->_catalogproduct->load($productId);
		if ($catalogModel->getTypeId() == 'booking') {

			if (isset($post[0]) && count($post[0])!=0) {


				foreach ($post[0] as $postvalue) {

					if (isset($postvalue['category'])) {
						$categoryModel = $this->_objectManager->create('Ced\Booking\Model\RoomTypeCategory')->load($postvalue['category'],'title');
					}
					if (isset($postvalue['type'])) {
						$roomTypeModel = $this->_objectManager->create('Ced\Booking\Model\Roomtype')->load($postvalue['type'],'title');
					}

					$categoryModel = isset($categoryModel) && $categoryModel!=''?$categoryModel:'';
					$roomTypeModel = isset($roomTypeModel) && $roomTypeModel !=''?$roomTypeModel:''; 

					if (isset($postvalue['id']) && $postvalue['id'] != 0 && !isset($postvalue['delete'])) {

						$model = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($postvalue['id']);
						$roomId = $postvalue['id'];
						
						$model->setData('product_id',$productId)
			              ->setData('room_category_id',$categoryModel->getId())
			              ->setData('room_type_id',$roomTypeModel->getId())
			              ->setData('status',$postvalue['status'])
			              ->setData('price',$postvalue['price'])
			              ->setData('min_booking_allowed_days',$postvalue['min_booking_allowed_days'])
			              ->setData('max_booking_allowed_days',$postvalue['max_booking_allowed_days'])
			              ->setData('description',$postvalue['description']);
			        	$model->save(); 

					} else if (isset($postvalue['id']) && $postvalue['id'] == 0 && !isset($postvalue['delete'])) {

						$model = $this->_objectManager->create('Ced\Booking\Model\Rooms');

						$model->setData('product_id',$productId)
			              ->setData('room_category_id',$categoryModel->getId())
			              ->setData('room_type_id',$roomTypeModel->getId())
			              ->setData('status',$postvalue['status'])
			              ->setData('price',$postvalue['price'])
			              ->setData('min_booking_allowed_days',$postvalue['min_booking_allowed_days'])
			              ->setData('max_booking_allowed_days',$postvalue['max_booking_allowed_days'])
			              ->setData('description',$postvalue['description']);
			        	$model->save(); 
			        	//print_r($model->getData());
			        	//$roomData = end($model->getData());
			        	$roomId = $model['id'];

					} else if (isset($postvalue['delete']) && $postvalue['delete'] == true) {

						$model = $this->_objectManager->create('Ced\Booking\Model\Rooms')->load($postvalue['id'])->delete();
					}

					if (isset($roomId)) {

		        		$roomCount = 0;

		        		$RoomAmenitiesModel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities')->load($roomId,'room_id')->delete();

		        		if (isset($postvalue['facilitiesIds']) && $postvalue['facilitiesIds']!='') {

						        
							    foreach ($postvalue['facilitiesIds'] as $ids) {

							        $RoomAmenitiesModel = $this->_objectManager->create('Ced\Booking\Model\RoomAmenities');

							        $RoomAmenitiesCollection = $RoomAmenitiesModel->getCollection()->addFieldToFilter('room_id',$roomId)->addFieldToFilter('amenity_id',$ids);
							        if (count($RoomAmenitiesCollection)==0) {
							        	$RoomAmenitiesModel->setData('room_id',$roomId)
							        					   ->setData('amenity_id',$ids)
							        					   ->save();
							        }
							    }
					    }

				        if(isset($postvalue['images']) && $postvalue['images']!='') {

					        foreach ($postvalue['images'] as $key=>$image) {
					        	$RoomImagesModel = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation');

					        	$image_name = !isset($image['file']) ? $image['name'] : $image['file'];

					        	$roomImageCollection = $RoomImagesModel->getCollection()->addFieldToFilter('room_id',$roomId)->addFieldToFilter('image_name',$image_name);
					        	if (count($roomImageCollection)==0) {

					        		$RoomImagesModel->setData('room_id',$roomId)
					        						->setData('image_name',$image_name)
						        	                ->save();
					        	}
					        	
					        }
			    		}

			    		if(isset($postvalue['deletedImage']) && $postvalue['deletedImage']!='') {

					        foreach ($postvalue['deletedImage'] as $id) {

					        	$RoomImagesModel = $this->_objectManager->create('Ced\Booking\Model\RoomsImageRelation')->load($id);

					        	$RoomImagesModel->delete();
					        	
					        }
			    		}

			    		if (isset($postvalue['roomNumbers']) && $postvalue['roomNumbers']!='') {

			    			$roomCount = count($postvalue['roomNumbers'])+$roomCount;

						    foreach ($postvalue['roomNumbers'] as $number) {
						    	
						    
						        if (isset($number['value'])) {

						        	if (preg_match('/edit_/',$number['id'])) {
						        		 $value = explode('_',$number['id']);
						        		 $id = $value[1];
						        		$RoomNumbersModel = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->load($id);

							        	$RoomNumbersModel->setData('room_id',$roomId)
							        					 ->setData('room_numbers',$number['value'])
							        					 ->save();

						        	}  else {

							        	$RoomNumbersModel = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers');

							        	$RoomNumbersModel->setData('room_id',$roomId)
							        					 ->setData('room_numbers',$number['value'])
							        					 ->save();
							        }
							    }
						    }
					    }

					    if (isset($postvalue['deletedRoomNumbers']) && $postvalue['deletedRoomNumbers']!='') {

						    foreach ($postvalue['deletedRoomNumbers'] as $id) {

						        $DelRoomNumbersModel = $this->_objectManager->create('Ced\Booking\Model\RoomNumbers')->load($id)->delete();

						    }
					    }

					    $daysarray = [];


					    if ((isset($postvalue['excludeDaysCheckIn']) && isset($postvalue['excludeDaysCheckOut'])) && ($postvalue['excludeDaysCheckIn']!='' && $postvalue['excludeDaysCheckOut']!='')) {

						    foreach ($postvalue['excludeDaysCheckIn'] as $checkin) {

						    	foreach ($postvalue['excludeDaysCheckOut'] as $checkout) {

						    		if ($checkin['id'] == $checkout['id']) {

						    			$daysarray[] = ['id' => $checkin['id'],
						    							'check-in'=> $checkin['value'],
						    						    'check-out'=> $checkout['value']
						    						   ];
						    		}

						    	}
						    }

						    if (count($daysarray) != 0) {
						    	foreach ($daysarray as $arr) {

							        $excludeDaysCollection = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->getCollection()->addFieldToFilter('id',$arr['id']);

							        if (count($excludeDaysCollection)!=0) {

							        	$excludeDaysModel = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->load($arr['id']);


							        	$excludeDaysModel->setData('room_id',$roomId)
							        					 ->setData('day_start',$arr['check-in'])
							        					 ->setData('day_end',$arr['check-out'])
							        					 ->save();
							        } else {

							        	$excludeDaysModel = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays');


							        	$excludeDaysModel->setData('room_id',$roomId)
							        					 ->setData('day_start',$arr['check-in'])
							        					 ->setData('day_end',$arr['check-out'])
							        					 ->save();

							        }

						    	}
						    }
						    
					    }

					    if (isset($postvalue['deletedRoomExcludeDays']) && $postvalue['deletedRoomExcludeDays']!='') {

						    foreach ($postvalue['deletedRoomExcludeDays'] as $id) {

						        $DelExcludeDaysModel = $this->_objectManager->create('Ced\Booking\Model\RoomExcludeDays')->load($id)->delete();
						    }
					    }

		        		$productdata->setData('room_count',$roomCount);
		        	}
			    }
			}


		if(isset($post['links']['booking'])) {

            $FacilitiesCollection = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('product_id', $productId);
            $savedFacilityIds = $FacilitiesCollection->getColumnValues('facility_id');
            $postedFacilityId = [];
            foreach ($post['links']['booking'] as $postedFacility) {
                $postedFacilityId[] = $postedFacility['id'];
            }
            $deleteFacilities = array_diff($savedFacilityIds, $postedFacilityId);
            $insertFacilities = array_diff($postedFacilityId, $savedFacilityIds);
            foreach ($FacilitiesCollection as $facility) {
                if (in_array($facility->getFacilityId(), $deleteFacilities)) {
                    $model = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->load($facility->getId())->delete();
                }
            }

            foreach ($insertFacilities as $facilityId) {
                $FacilitiesModel = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation');
                $FacilitiesModel->setData('product_id', $productId)
                    ->setData('facility_id', $facilityId)
                    ->save();
            }
        } else {

        	$FacilitiesCollection = $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->getCollection()->addFieldToFilter('product_id', $productId);
            $savedFacilityIds = $FacilitiesCollection->getColumnValues('id');
            if (count($savedFacilityIds))
            {
	            foreach ($savedFacilityIds as $id) {
	                $this->_objectManager->create('Ced\Booking\Model\ProductFacilityRelation')->load($id)->delete();
	            }
	        }
        }
		}
	
	} 

}


	
