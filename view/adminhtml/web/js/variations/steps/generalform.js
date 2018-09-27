/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
// jscs:disable jsDoc
define([
    'uiComponent',
    'jquery',
    'underscore',
    'mage/translate'
], function (Component, $, _) {
    'use strict';

    var initNewAttributeListener = function (provider) {
        $('[data-role=product-variations-matrix]').on('add', function () {
            provider().reload();
        });
    };

    return Component.extend({
        attributesLabels: {},
        stepInitialized: false,
        defaults: {
            notificationMessage: {
                text: null,
                error: null
            },
            selectedAttributes: []
        },
        initialize: function () {
            this._super();
            this.selected = [];

            initNewAttributeListener(this.attributeProvider);
        },
        initObservable: function () {
            this._super().observe(['selectedAttributes']);

            return this;
        },
        render: function (wizard) {
            
            this.wizard = wizard;
            $('.action-cancel.action-tertiary').hide();
             if ($('#edit_room_id').length == 0) {
                $('.action-close').css('margin-right','auto');
                $('.action-close').css('margin-top','auto');
            }
             $("#page_price").keyup(function(evt) {
                    var self = $(this);
                    self.val(self.val().replace(/[^0-9\.]/g, ''));
                    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
                    {
                      evt.preventDefault();
                    }
            });

             $("#page_min_booking_allowed_days").keyup(function(evt) {
               var self = $(this);
               self.val(self.val().replace(/\D/g, ''));
               if ((evt.which < 48 || evt.which > 57)) 
               {
                 evt.preventDefault();
               }
             });
             $("#page_max_booking_allowed_days").keyup(function(evt) {
               var self = $(this);
               self.val(self.val().replace(/\D/g, ''));
               if ((evt.which < 48 || evt.which > 57)) 
               {
                 evt.preventDefault();
               }
             });
             $("#edit_room_page_price").keyup(function(evt) {
                    var self = $(this);
                    self.val(self.val().replace(/[^0-9\.]/g, ''));
                    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
                    {
                      evt.preventDefault();
                    }
            });

             $("#edit_room_page_min_booking_allowed_days").keyup(function(evt) {
               var self = $(this);
               self.val(self.val().replace(/\D/g, ''));
               if ((evt.which < 48 || evt.which > 57)) 
               {
                 evt.preventDefault();
               }
             });
             $("#edit_room_page_max_booking_allowed_days").keyup(function(evt) {
               var self = $(this);
               self.val(self.val().replace(/\D/g, ''));
               if ((evt.which < 48 || evt.which > 57)) 
               {
                 evt.preventDefault();
               }
             });
        },
        doSelectSavedAttributes: function () {
            if (this.stepInitialized === false) {
                this.stepInitialized = true;
                //cache attributes labels, which can be present on the 2nd page
                _.each(this.initData.attributes, function (attribute) {
                    this.attributesLabels[attribute.id] = attribute.label;
                }.bind(this));
                this.multiselect().selected(_.pluck(this.initData.attributes, 'id'));
            }
        },
        doSelectedAttributesLabels: function (selected) {
            var labels = [];

            this.selected = selected;
            _.each(selected, function (attributeId) {
                var attribute;

                if (!this.attributesLabels[attributeId]) {
                    attribute = _.findWhere(this.multiselect().rows(), {
                        attribute_id: attributeId
                    });

                    if (attribute) {
                        this.attributesLabels[attribute.attribute_id] = attribute.frontend_label;
                    }
                }
                labels.push(this.attributesLabels[attributeId]);
            }.bind(this));
            this.selectedAttributes(labels.join(', '));
        },
        force: function (wizard) {

           // console.log(parseInt($('#edit_room_page_max_booking_allowed_days').val()));
            //console.log(parseInt($('#edit_room_page_min_booking_allowed_days').val()));

            if ($('#edit_room_id').length != 0) {

                    if ($('#edit_room_page_price').val()=='') {
                        throw new Error($.mage.__('Please Enter Room Price.'));
                    }
                    if ( $('#edit_room_page_min_booking_allowed_days').val()=='') {
                        throw new Error($.mage.__('Please Enter Room Minimum Booking Allowed Days.'));
                    }
                    if ( $('#edit_room_page_max_booking_allowed_days').val()=='') {
                        throw new Error($.mage.__('Please Enter Room Maximum Booking Allowed Days.'));
                    }

                    if (parseInt($('#edit_room_page_max_booking_allowed_days').val()) < parseInt($('#edit_room_page_min_booking_allowed_days').val())) {
                        throw new Error($.mage.__('Maximum booking allowed days should be greather than minimum booking allowed days'));
                    }


            }

            if ($('#edit_room_id').length == 0) {
                console.log('add');
                if ($('#page_room_category').val()=='') {

                    throw new Error($.mage.__('Please select Room Category.'));
                } else {
                   
                    if ($('#page_price').val()=='') {
                        alert('Please Enter Room Price.');
                        throw new Error($.mage.__('Please Enter Room Price.'));
                    }
                    if ( $('#page_min_booking_allowed_days').val()=='') {
                        alert('Please Enter Room Minimum Booking Allowed Days.');
                        throw new Error($.mage.__('Please Enter Room Minimum Booking Allowed Days.'));
                    }
                    if ( $('#page_max_booking_allowed_days').val()=='') {
                         alert('Please Enter Room Maximum Booking Allowed Days.');
                        throw new Error($.mage.__('Please Enter Room Maximum Booking Allowed Days.'));
                    }
                    if (parseInt($('#page_max_booking_allowed_days').val()) < parseInt($('#page_min_booking_allowed_days').val())) {
                         alert('Maximum booking allowed days should be greather than minimum booking allowed days.');
                        throw new Error($.mage.__('Maximum booking allowed days should be greather than minimum booking allowed days.'));
                    }
                }
                if ($('#booking_rooms_json_data').val() != '') {

                    console.log("addjson");

                    var resultdata = JSON.parse($('#booking_rooms_json_data').val());

                    if (resultdata.length>0) {
                        for (var i = 0; i < resultdata.length; i++) {
                             var category = resultdata[i].room_category;
                             var type = resultdata[i].room_type;

                             if (category == $('#page_room_category').val() && type == $('#page_room_type').val()) {
                                alert('This Room is already exist, Please select another room type.');
                                 throw new Error($.mage.__('This Room is already exist, Please select another room type.'));
                             } 
                        }
                    }
                }
            }
            
        },
        back: function () {
        }
    });
});
