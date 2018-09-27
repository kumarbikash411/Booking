/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global FORM_KEY*/
// jscs:disable jsDoc
define([
    'uiComponent',
    'jquery',
    'ko',
    'underscore',
    'Magento_Ui/js/lib/collapsible',
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'Ced_Booking/js/product-gallery',
    'jquery/file-uploader',
    'mage/translate',
    'Ced_Booking/js/variations/variations'
], function (Component, $, ko, _, Collapsible, mageTemplate, alert) {
    'use strict';
    var roomimagedata =[];
    var datavariations =[];
    var bookingvariations = {};
    var saveddata = [];
    var flag=false;
    var facilityIds = [];
    var deletedImage = [];
    var excludeDaysCheckIn = [];
    var excludeDaysCheckOut = [];
    var deletedRoomExcludeDays = [];


    var ids = '';
    return Component.extend({
        defaults: {
            modules: {
                variationsComponent: '${ $.variationsComponent }',
                modalComponent: '${ $.modalComponent }',
                BookingUrl: '${$.RoomUrl}'
            },
            countVariations: 0,
            attributes: [],
            sections: {},
            images: null,
            price: '',
            quantity: '',
            gridExisting: [],
            gridNew: [],
            gridDeleted: [],
            attributesName: [$.mage.__('Images'), $.mage.__('SKU'), $.mage.__('Quantity'), $.mage.__('Price')],
            gridTemplate: 'Magento_ConfigurableProduct/variations/steps/summary-grid',
            notificationMessage: {
                text: null,
                error: null
            }
        },
        initObservable: function () {
            this._super().observe('countVariations gridExisting gridNew gridDeleted attributes sections');
            this.gridExisting.columns = ko.observableArray();
            this.gridNew.columns = ko.observableArray();
            this.gridDeleted.columns = ko.observableArray();

            return this;
        },
        initialize: function () {
            var self = this;
            this._super();
            this.sections({
                images: {
                    label: 'images',
                    type: ko.observable('none'),
                    value: ko.observable(),
                    attribute: ko.observable()
                },
                price: {
                    label: 'price',
                    type: ko.observable('none'),
                    value: ko.observable(),
                    attribute: ko.observable(),
                    currencySymbol: ''
                },
                quantity: {
                    label: 'quantity',
                    type: ko.observable('none'),
                    value: ko.observable(),
                    attribute: ko.observable()
                }

            });

            // this.variationsComponent(function (variationsComponent) {
            //     this.sections().price.currencySymbol = variationsComponent.getCurrencySymbol()
            // }.bind(this));

            this.makeOptionSections = function () {
                this.images = new self.makeImages(null);
                this.price = self.price;
                this.quantity = self.quantity;
            };
            this.makeImages = function (images, typePreview) {
                var preview;

                if (!images) {
                    this.images = [];
                    this.preview = self.noImage;
                    this.file = null;
                } else {
                    this.images = images;
                    preview = _.find(this.images, function (image) {
                        return _.contains(image.galleryTypes, typePreview);
                    });

                    if (preview) {
                        this.file = preview.file;
                        this.preview = preview.url;
                    } else {
                        this.file = null;
                        this.preview = self.noImage;
                    }
                }
            };
            this.images = new this.makeImages();
            _.each(this.sections(), function (section) {
                section.type.subscribe(function () {
                    this.setWizardNotifyMessageDependOnSectionType();
                }.bind(this));
            }, this);
        },
        types: ['each', 'single', 'none'],
        setWizardNotifyMessageDependOnSectionType: function () {
            var flag = false;

            _.each(this.sections(), function (section) {
                if (section.type() !== 'none') {
                    flag = true;
                }
            }, this);

            if (flag) {
                this.wizard.setNotificationMessage(
                    $.mage.__('Choose this option to delete and replace extension data ' +
                    'for all past configurations.')
                );
            } else {
                this.wizard.cleanNotificationMessage();
            }
        },
        // requestAttributes: function (attributeIds) {
        //     $.ajax({
        //         type: 'POST',
        //         url: this.optionsUrl,
        //         data: {
        //             attributes: attributeIds
        //         },
        //         showLoader: true
        //     }).done(function (attributes) {
        //         attributes = _.sortBy(attributes, function (attribute) {
        //             return this.wizard.data.attributesIds.indexOf(attribute.id);
        //         }.bind(this));
        //         this.attributes(_.map(attributes, this.createAttribute));
        //     }.bind(this));
        // },
        variations: [],
        // generateGrid: function (variations) {
        //     var productSku = this.variationsComponent().getProductValue('sku'),
        //         productPrice = this.variationsComponent().getProductPrice(),
        //         productWeight = this.variationsComponent().getProductValue('weight'),
        //         variationsKeys = [],
        //         gridExisting = [],
        //         gridNew = [],
        //         gridDeleted = [];
        //     this.variations = [];

        //     _.each(variations, function (options) {

        //         var product, images, type, category, price,status,
        //             productId = this.variationsComponent().getProductIdByOptions(options);


        //         if (productId) {
        //             product = _.findWhere(this.variationsComponent().variations, {
        //                 productId: productId
        //             });
        //         }
        //         images = options.images;
        //         type = options.type;
        //         category = options.category;
        //         status = options.status;
        //         price = options.price;

        //         if (!price) {
        //             price = productId ? product.price : productPrice;
        //         }

        //         if (productId && !images.file) {
        //             images = product.images;
        //         }
        //         variation = {
        //             images: images,
        //             type: type,
        //             category: category,
        //             price: price,
        //             status: status,
        //             editable: false
        //         };

        //         // if (productId) {
        //         //     variation.type = product.sku;
        //         //     variation.weight = product.weight;
        //         //     variation.category = product.name;
        //         //     gridExisting.push(this.prepareRowForGrid(variation));
        //         // } else {
        //             //gridNew.push(this.prepareRowForGrid(variation));
        //        // }
        //         this.variations.push(variation);
        //         //variationsKeys.push(this.variationsComponent().getVariationKey(options));
        //     }, this);

        //    // this.gridExisting(gridExisting);
        //     //this.gridExisting.columns(this.getColumnsName(this.wizard.data.attributes));

        //     if (gridNew.length > 0) {
        //         this.gridNew(gridNew);
        //         //this.gridNew.columns(this.getColumnsName(this.wizard.data.attributes));
        //     }

        //     // _.each(_.omit(this.variationsComponent().productAttributesMap, variationsKeys), function (productId) {
        //     //     gridDeleted.push(this.prepareRowForGrid(
        //     //         _.findWhere(this.variationsComponent().variations, {
        //     //             productId: productId
        //     //         })
        //     //     ));
        //     // }.bind(this));

        //     // if (gridDeleted.length > 0) {
        //     //     this.gridDeleted(gridDeleted);
        //     //     this.gridDeleted.columns(this.getColumnsName(this.variationsComponent().productAttributes));
        //     // }
        // },
        // prepareRowForGrid: function (variation) {
        //     var row = [];
        //     row.push(_.extend({
        //         images: []
        //     }, variation.images));
        //     row.push(variation.type);
        //     row.push(variation.category);
        //     _.each(variation.images, function (option) {
        //         row.push(option.file);
        //     });
        //    // row.push(this.variationsComponent().getCurrencySymbol() +  ' ' + variation.price);

        //     return row;
        // },
        getColumnsName: function (attributes) {

            var columns = this.attributesName.slice(0);

            // attributes.each(function (attribute, index) {
            //     columns.splice(3 + index, 0, attribute.label);
            // }, this);

            return columns;
        },
        getGridTemplate: function () {
            return this.gridTemplate;
        },
        render: function (wizard) {
            this.wizard = wizard;
            //ids = wizard.data.attributesIds();
            //this.requestAttributes(wizard.data.attributesIds());
            this.bindGalleries();
            this.gridNew([]);
            this.gridExisting([]);
            this.gridDeleted([]);


           // this.attributes(wizard.data.attributes());
            // this.attributes(wizard.data.attributes());

            //  if (this.mode === 'edit') {
            //      this.setWizardNotifyMessageDependOnSectionType();
            //  }
            //  //fill option section data
            //  this.attributes.each(function (attribute) {
            //      attribute.chosen.each(function (option) {
            //          option.sections = ko.observable(new this.makeOptionSections());
            //      }, this);
            //  }, this);
            //  //reset section.attribute
            // _.each(this.sections(), function (section) {
            //      section.attribute(null);
            //  });

            // this.initCountVariations();
            
        },
        // initCountVariations: function () {
        //     var variations = this.generateVariation(this.attributes()),
        //         newVariations = _.map(variations, function (options) {
        //             return this.variationsComponent().getVariationKey(options);
        //         }.bind(this)),
        //         existingVariations = _.keys(this.variationsComponent().productAttributesMap);
        //     this.countVariations(_.difference(newVariations, existingVariations).length);
        // },

        /**
         * @param attributes example [['b1', 'b2'],['a1', 'a2', 'a3'],['c1', 'c2', 'c3'],['d1']]
         * @returns {*} example [['b1','a1','c1','d1'],['b1','a1','c2','d1']...]
         */
        // generateVariation: function (attributes) {
        //     return _.reduce(attributes, function (matrix, attribute) {
        //         var tmp = [];
        //         _.each(matrix, function (variations) {
        //             _.each(attribute.chosen, function (option) {
        //                 option.attribute_code = attribute.code;
        //                 option.attribute_label = attribute.label;
        //                 tmp.push(_.union(variations, [option]));
        //             });
        //         });

        //         if (!tmp.length) {
        //             return _.map(attribute.chosen, function (option) {
        //                 option.attribute_code = attribute.code;
        //                 option.attribute_label = attribute.label;

        //                 return [option];
        //             });
        //         }

        //         return tmp;
        //     }, []);
        // },
        // getSectionValue: function (section, options) {
        //     switch (this.sections()[section].type()) {
        //         case 'each':
        //             return _.find(this.sections()[section].attribute().chosen, function (chosen) {
        //                 return _.find(options, function (option) {
        //                     return chosen.id == option.id;
        //                 });
        //             }).sections()[section];

        //         case 'single':
        //             return this.sections()[section].value();

        //         case 'none':
        //             return this[section];
        //     }
        // },
        getImageProperty: function (node) {
            var types = node.find('[data-role=gallery]').productGallery('option').types,
                images = _.map(node.find('[data-role=image]'), function (image) {
                var imageData = $(image).data('imageData');
                imageData.galleryTypes = _.pluck(_.filter(types, function (type) {
                    return type.value === imageData.file;
                }), 'code');

                return imageData;
            });

            return _.reject(images, function (image) {
                return !!image.isRemoved;
            });
        },
        fillImagesSection: function () {
            switch (this.sections().images.type()) {
                case 'each':
                    if (this.sections().images.attribute()) {
                        this.sections().images.attribute().chosen.each(function (option) {
                            option.sections().images = new this.makeImages(
                                this.getImageProperty($('[data-role=step-gallery-option-' + option.id + ']')),
                                'thumbnail'
                            );
                        }, this);
                    }
                    break;

                case 'single':
                    this.sections().images.value(new this.makeImages(
                        this.getImageProperty($('[data-role=step-gallery-single]')),
                        'thumbnail'
                    ));
                    break;

                default:
                    this.sections().images.value(new this.makeImages());
                    break;
            }
        },
        force: function (wizard) {
            
            //console.log("remove");
            this.fillImagesSection();
            this.roomimages = roomimagedata;

            $('.removed input[class^="removed-image-"]').each(function() {

                if (this.value!='') {
                    deletedImage.push(this.value);
                }
            });
            var roomNumbers = [];
            var deletedRoomNumbers = [];
            var editfalg = false;
            $('input[name^="room_number_"]').each(function(){

                if (this.value!='') { 

                    if ($(this).parent().parent().attr('class') == 'no-display template') {

                        
                           deletedRoomNumbers.push(this.name.replace('room_number_',''));
                    } else {

                            var roomnumbersdata = { 'id': this.name.replace('room_number_',''),
                                                    'value' : this.value
                                                };
                       
                            roomNumbers.push(roomnumbersdata);
                    }
                }

               
            });



            $('input[name^="exclude_days_check_in_"]').each(function(){

                if (this.value!='') { 

                    if ($(this).parent().parent().attr('class') == 'no-display template') {

                        deletedRoomExcludeDays.push(this.name.replace('exclude_days_check_in_',''));

                    }else {
                            var CheckInvalues = {
                                'id' : this.name.replace('exclude_days_check_in_',''),
                                'value' : this.value

                            };

                        excludeDaysCheckIn.push(CheckInvalues);
                    }

                    
                }
               
            });

            $('input[name^="exclude_days_check_out_"]').each(function(){

                if (this.value!='') { 

                    var CheckOutvalues = {
                        'id' : this.name.replace('exclude_days_check_out_',''),
                        'value' : this.value

                    };

                    excludeDaysCheckOut.push(CheckOutvalues);
                }
            });
            

            $('input[name="title"]:checked').each(function() {
                
                facilityIds.push(this.id.replace('page_',''));
            });

            console.log(facilityIds);
            if ($('#edit_room_page_price').length > 0) {
                if ($('#edit_room_page_price').val() == '') {
                    var price = '0';
                } else {
                    var price = $('#edit_room_page_price').val();
                }
            } else {
                if ($('#page_price').val() == '') {
                    var price = '0';
                } else {
                    var price = $('#page_price').val();
                } 
            }

            
           

            if ($('#room_data').val() != '') {

                var saveddata = JSON.parse($('#room_data').val());

                var test1 = [];
                var temp = {};

                
                if (flag == false) {
                    for (var i = 0; i < saveddata.length; i++) {

                        flag = true;
                        var temp = test1[i] = {
                            "images": '',
                            "id":saveddata[i].id,
                            "type": saveddata[i].type,
                            "category": saveddata[i].category,
                            "price": saveddata[i].price,
                            "status": saveddata[i].status,
                            "min_booking_allowed_days": saveddata[i].min_booking_allowed_days,
                            "max_booking_allowed_days": saveddata[i].max_booking_allowed_days,
                            "description" : saveddata[i].description,
                            "facilityIds": '',
                            "roomNumbers": saveddata[i].roomNumbers,
                            "deletedImage": '',
                            "deletedRoomNumbers": '',
                            "excludeDaysCheckIn" : '',
                            "excludeDaysCheckOut" : '',
                            "deletedRoomExcludeDays" : '',
                            "productId": saveddata[i].product_id,
                            "editable": true
                        };

                        datavariations.push(temp);
                        
                    }

                }
            }

            if ($('#booking_room_id').length > 0) {

                if (datavariations.length>0) {

                    for (var i = 0; i < datavariations.length; i++) {

                       if (datavariations[i].id == $('#booking_room_id').val()) {

                                datavariations[i].images = this.roomimages,
                                datavariations[i].type = $('#edit_room_page_room_type').val(),
                                datavariations[i].category = $('#edit_room_page_room_category').val(),
                                datavariations[i].price = price,
                                datavariations[i].status = $('#edit_room_page_status').val(),
                                datavariations[i].min_booking_allowed_days = $('#edit_room_page_min_booking_allowed_days').val(),
                                datavariations[i].max_booking_allowed_days = $('#edit_room_page_max_booking_allowed_days').val(),
                                datavariations[i].description  = $('#edit_room_page_description').val(),
                                datavariations[i].facilityIds = facilityIds,
                                datavariations[i].roomNumbers = roomNumbers,
                                datavariations[i].deletedRoomNumbers = deletedRoomNumbers,
                                datavariations[i].deletedImage = deletedImage,
                                datavariations[i].excludeDaysCheckIn = excludeDaysCheckIn,
                                datavariations[i].excludeDaysCheckOut = excludeDaysCheckOut,
                                datavariations[i].deletedRoomExcludeDays = deletedRoomExcludeDays,
                                datavariations[i].productId = null,
                                datavariations[i].editable = true
                           
                        }
                    }
                }
            }
            

            if ($('#booking_room_id').length == 0) {

                        var type = $('#page_room_type').val();
                        var category = $('#page_room_category').val();
                        var status = $('#page_status').val();
                        var minDays = $('#page_min_booking_allowed_days').val();
                        var maxDays = $('#page_max_booking_allowed_days').val();
                        var desc = $('#page_description').val();
                    bookingvariations = {
                        "images": this.roomimages,
                        "id":0,
                        "type": type,
                        "category": category,
                        "price": price,
                        "status": status,
                        "min_booking_allowed_days": minDays,
                        "max_booking_allowed_days": maxDays,
                        "description" : desc,
                        "facilityIds": facilityIds,
                        "roomNumbers": roomNumbers,
                        "deletedRoomNumbers": deletedRoomNumbers,
                        "excludeDaysCheckIn" : excludeDaysCheckIn,
                        "excludeDaysCheckOut" : excludeDaysCheckOut,
                        "deletedRoomExcludeDays" : deletedRoomExcludeDays,
                        "deletedImage":deletedImage,
                        "productId": null,
                        "editable": true
                    };
                
                datavariations.push(bookingvariations);
            }
            console.log(datavariations);
            this.variationsComponent().render(datavariations);

            if ($('#edit_room_id').length > 0) {
                $( "[id^=roompop]" ).trigger( "closeModal" );
            }
            $('input[name="title"]').removeAttr('checked');
            
            facilityIds = [];
            roomimagedata = [];
            excludeDaysCheckIn = [];
            excludeDaysCheckOut = [];

            // if ($('#edit_room_id').length == 0) {
            //     setTimeout(function(){
            //         $('.edit_room_btn').hide();
            //     },3000);
            // }
            
            this.modalComponent().closeModal();


        },
        validate: function () {
            var formValid;
            _.each(this.sections(), function (section) {
                switch (section.type()) {
                    case 'each':
                        if (!section.attribute()) {
                            throw new Error($.mage.__('Please select attribute for {section} section.')
                                .replace('{section}', section.label));
                        }
                        break;

                    case 'single':
                        if (!section.value()) {
                            throw new Error($.mage.__('Please fill in the values for {section} section.')
                                .replace('{section}', section.label));
                        }
                        break;
                }
            }, this);
            formValid = true;
            _.each($('[data-role=attributes-values-form]'), function (form) {
                formValid = $(form).valid() && formValid;
            });

            if (!formValid) {
                throw new Error($.mage.__('Please fill-in correct values.'));
            }
        },
        validateImage: function () {
            switch (this.sections().images.type()) {
                case 'each':
                    _.each(this.sections()['images'].attribute().chosen, function (option) {
                        if (!option.sections().images.images.length) {
                            throw new Error($.mage.__('Please select image(s) for your attribute.'));
                        }
                    });
                    break;

                case 'single':
                    if (this.sections().images.value().file == null) {
                        throw new Error($.mage.__('Please choose image(s).'));
                    }
                    break;
            }
        },
        back: function () {
            //this.setWizardNotifyMessageDependOnSectionType();
        },
        bindGalleries: function () {
            $('[data-role=bulk-step] [data-role=gallery]').each(function (index, element) {
                var gallery = $(element),
                    uploadInput = $(gallery.find('[name=image]')),
                    dropZone = $(gallery).find('.image-placeholder');

                if (!gallery.data('gallery-initialized')) {
                    gallery.mage('productGallery', {
                        template: '[data-template=gallery-content]',
                        dialogTemplate: '.dialog-template',
                        dialogContainerTmpl: '[data-role=img-dialog-container-tmpl]'
                    });

                    uploadInput.fileupload({

                        dataType: 'json',
                        dropZone: dropZone,
                        process: [{
                            action: 'load',
                            fileTypes: /^image\/(gif|jpeg|png)$/
                        }, {
                            action: 'resize',
                            maxWidth: 1920,
                            maxHeight: 1200
                        }, {
                            action: 'save'
                        }],
                        formData: {form_key: FORM_KEY},
                        sequentialUploads: true,
                        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                        add: function (e, data) {
                            var progressTmpl = mageTemplate('[data-template=uploader]'),
                                fileSize,
                                tmpl;

                            $.each(data.files, function (index, file) {
                                fileSize = typeof file.size == "undefined" ?
                                    $.mage.__('We could not detect a size.') :
                                    byteConvert(file.size);

                                data.fileId = Math.random().toString(33).substr(2, 18);

                                tmpl = progressTmpl({
                                    data: {
                                        name: file.name,
                                        size: fileSize,
                                        id: data.fileId
                                    }
                                });

                                $(tmpl).appendTo(gallery.find('[data-role=uploader]'));
                            });

                            $(this).fileupload('process', data).done(function () {
                                data.submit();
                            });
                        },
                        done: function (e, data) {

                            roomimagedata.push(data.result);
                            
                            if (data.result && !data.result.error) {
                                gallery.trigger('addItem', data.result);

                            } else {
                                $('#' + data.fileId)
                                    .delay(2000)
                                    .hide('highlight');
                                alert({
                                    content: $.mage.__('We don\'t recognize or support this file extension type.')
                                });
                            }
                            $('#' + data.fileId).remove();
                        },
                        progress: function (e, data) {
                            var progress = parseInt(data.loaded / data.total * 100, 10),
                                progressSelector = '#' + data.fileId + ' .progressbar-container .progressbar';

                            $(progressSelector).css('width', progress + '%');
                        },
                        fail: function (e, data) {
                            var progressSelector = '#' + data.fileId;

                            $(progressSelector).removeClass('upload-progress').addClass('upload-failure')
                                .delay(2000)
                                .hide('highlight')
                                .remove();
                        }
                    });
                    gallery.data('gallery-initialized', 1);
                }
            });
        }
    });
});
