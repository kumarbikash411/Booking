<?php 

namespace Ced\Booking\Model\Config\Product\Frontend;

    class Time extends \Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend
    {
        /**
         * Retrieve attribute value
         *
         * @param \Magento\Framework\DataObject $object
         * @return mixed
         */
        public function getValue(\Magento\Framework\DataObject $object)
        {
            // $data = '';
            // $value = parent::getValue($object);
            // if ($value) {
            //     $data = '<b>' . $value . '</b>';
            // }
            $data = '<div>
                        <button>Time Picker</button>
                    </div>';
            return $data;
        }
    }