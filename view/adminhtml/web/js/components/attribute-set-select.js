/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/ui-select',
    'jquery'
], function (Select,$) {
    'use strict';

    return Select.extend({
        defaults: {
            listens: {
                'value': 'changeFormSubmitUrl'
            },
            modules: {
                formProvider: '${ $.provider }'
            }
        },

        /**
         * Change set parameter in save and validate urls of form
         *
         * @param {String|Number} value
         */
        changeFormSubmitUrl: function (value) {

            var hideButtons = setInterval(function() {
                
            
                if ($('[data-index="booking-general-information"]').length > 0) {
                    if ($('[data-role="selected-option"]').text() == 'Daily Rent Booking' || $('[data-role="selected-option"]').text() == 'Hourly Rent Booking') {
                        $('[data-index="booking"]').show();
                        $('[data-index="booking-general-information"]').show();
                        $('[data-index="room_button"]').hide();
                        $('[data-index="0"]').hide();

                    } else if ($('[data-role="selected-option"]').html() == 'Hotel Booking') {
                       
                        $('[data-index="booking"]').show();
                        $('[data-index="room_button"]').show();
                        $('[data-index="0"]').show();
                        $('[data-index="booking-general-information"]').show();

                    } else if ($('[data-role="selected-option"]').text() != 'Daily Rent Booking' && $('[data-role="selected-option"]').text() != 'Hourly Rent Booking' 
                        && $('[data-role="selected-option"]').html() != 'Hotel Booking') {

                        $('[data-index="booking"]').hide();
                        $('[data-index="booking-general-information"]').hide();
                    }
                    clearInterval(hideButtons);
                }
                
                   
            },1000);

            var pattern = /(set\/)(\d)*?\//,
                change = '$1' + value + '/';
            this.formProvider().client.urls.save = this.formProvider().client.urls.save.replace(pattern, change);
            this.formProvider().client.urls.beforeSave = this.formProvider().client.urls.beforeSave.replace(
                pattern,
                change
            );
        }
    });
});
