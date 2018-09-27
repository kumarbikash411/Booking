/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/modal/modal-component',
    'uiRegistry',
    'underscore'
], function ($,Modal, registry, _) {
    'use strict';

    return Modal.extend({
        defaults: {
            stepWizard: '',
            modules: {
                form: '${ $.formName }'
            }
        },

        /**
         * Open modal
         */
        openModal: function () {
           // $("input").val("");
            var stepWizard = {};

            this.form().validate();

            if (this.form().source.get('params.invalid') === false) {
                stepWizard = registry.get('index = ' + this.stepWizard);
  		       
                if (!_.isUndefined(stepWizard)) {
                    $('#page_room_category').val('');
                    $('#page_room_type').val('');
                    $('#page_status').val(1);
                    $('#page_price').val('');
                    $('#page_min_booking_allowed_days').val('');
                    $('#page_max_booking_allowed_days').val('');
                    $('#page_description').val('');
                    $('#page_max_allowed_adults').val('');
                    $('#page_max_allowed_child').val('');
                    $('[data-role="options-container"]').html('');
                    $('[data-role="exclude-days-options-container"]').html('');
                    $('[data-role="image"]').remove();
                     stepWizard.open();
		    	//var base_url = BASE_URL;
		       // var path = base_url.replace('catalog/index/index','booking/product/wizard');
		       // var previewPopup = $('<div/>',{id : 'roompop' });
		        /*var roompopup = previewPopup.modal({
		            type: 'slide',
		            title: 'Add Room',
		            innerScroll: true,
		            modalLeftMargin: 15,
		            buttons: [],
		            opened: function(rowIndex) { */
		                // $.ajax({
		                //     showLoader: true,
		                //     url: path,
		                //     type: 'POST',
		                //     data: {'form_key':FORM_KEY}
		                // }).done(function(a) {
                  //          // console.log(stepWizard);
		                //    // $('.product_form_product_form_bookingModal').trigger('openModal');
                  //          // $('.product_form_product_form_bookingModal').trigger('contentUpdated');
                  //           stepWizard.open();
		                // });
		            /*},
		            closed: function(rowIndex) { 
		                
		                previewPopup.parents('aside').remove();
		            }
		        }).trigger('openModal'); */
    
                }

                this._super();
                //$('.product_form_product_form_bookingModal').remove();
            }
        }
    });
});