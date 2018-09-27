/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'mageUtils',
    'uiLayout',
    'Ced_Booking/js/dynamic-rows/dynamic-rows',
    'jquery',
    'Magento_Ui/js/modal/modal',
], function (_, registry,utils, layout, dynamicRows,$,modal) {
    'use strict';

    var targetName = 'bookingModal';
    var roomIds = [];

    //$('[data-index="room_button"]').hide();

    return dynamicRows.extend({
        defaults: {
            actionsListOpened: false,
            canEditField: 'canEdit',
            newProductField: 'newProduct',
            dataScopeAssociatedProduct: 'data.associated_product_ids',
            dataProviderFromGrid: '',
            dataProviderChangeFromGrid: '',
            insertDataFromGrid: [],
            changeDataFromGrid: [],
            dataProviderFromWizard: '',
            recordData: [],
            insertDataFromWizard: [],
            map: null,
            isEmpty: true,
            isShowAddProductButton: false,
            cacheGridData: [],
            unionInsertData: [],
            deleteProperty: 'delete',
            dataLength: 0,
            identificationProperty: 'id',
            'attribute_set_id': '',
            attributesTmp: [],
            listens: {
                'insertDataFromGrid': 'processingInsertDataFromGrid',
                'insertDataFromWizard': 'processingInsertDataFromWizard',
                'unionInsertData': 'processingUnionInsertData',
                'changeDataFromGrid': 'processingChangeDataFromGrid',
                'isEmpty': 'changeVisibility'
            },
            imports: {
                'attribute_set_id': '${$.provider}:data.product.attribute_set_id'
            },
            'exports': {
                'attribute_set_id': '${$.provider}:data.new-variations-attribute-set-id'
            },
            modules: {
                //targetName: '${ $.targetName }',
                 modalWithGrid: '${ $.modalWithGrid }',
                gridWithProducts: '${ $.gridWithProducts}',
                bookingroommodal: '${ $.bookingroommodal }'
            },
            pages: 1,
            pageSize: 20,
            relatedData: [],
            currentPage: 1,
            startIndex: 0
        },

        /**
         * Invokes initialize method of parent class,
         * contains initialization logic
         */
        initialize: function () {
            this._super()
                .changeVisibility(this.isEmpty());

            return this;
        },

        /**
         * Change visibility
         *
         * When isEmpty = true, then visbible = false
         *
         * @param {Boolean} isEmpty
         */
        changeVisibility: function (isEmpty) {
            this.visible(!isEmpty);
        },

        /**
         * Open modal with grid.
         *
         * @param {String} rowIndex
         */
        openModalWithRoomsData: function (rowIndex) {

           var roomdata = this.source.get(this.dataScope + '.' + this.index + '.' + rowIndex),
                product = {
                    'id': roomdata.id,
                };


           if ($("#roompop"+rowIndex).length) {
                var previewAdded = $("#roompop"+rowIndex);
                previewAdded.modal({
                    opened: function(rowIndex) { }
                }).trigger('openModal');

            } else {
                var base_url = BASE_URL;
                var path = base_url.replace('catalog/index/index','booking/product/editwizard');
                var previewPopup = $('<div/>',{id : 'roompop'+rowIndex });
                var roompopup = previewPopup.modal({
                    type: 'slide',
                    title: 'Edit Room',
                    innerScroll: true,
                    modalLeftMargin: 15,
                    buttons: [],
                    opened: function(rowIndex) {
                        $.ajax({
                            showLoader: true,
                            url: path,
                            type: 'POST',
                            data: {'form_key':FORM_KEY,'Room_Id':product.id}
                        }).done(function(a) {
                            roompopup.append(a);
                        });
                    },
                    closed: function(rowIndex) { 
                        
                        previewPopup.parents('aside').remove();
                    }
                }).trigger('openModal');
            }
        },

        /**
         * Initialize children
         *
         * @returns {Object} Chainable.
         */
        initChildren: function () {
            var tmpArray = [];

            this.recordData.each(function (recordData) {
                tmpArray.push(recordData);
            }, this);

            this.unionInsertData(tmpArray);

            return this;
        },

        /**
         * Generate associated products
         */
        generateAssociatedProducts: function () {
            var productsIds = [];

            this.getUnionInsertData().forEach(function (data) {
                if (data.id !== null) {
                    productsIds.push(data.id);
                }
            });

            this.source.set(this.dataScopeAssociatedProduct, productsIds);
        },

        /**
         * Calls 'initObservable' of parent
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super()
                .observe([
                    'insertDataFromGrid', 'unionInsertData', 'isEmpty', 'isShowAddProductButton', 'actionsListOpened'
                ]);

            return this;
        },

        /**
         * Get union insert data from source
         *
         * @returns {Array}
         */
        getUnionInsertData: function () {
            var source = this.source.get(this.dataScope + '.' + this.index),
                result = [];

            _.each(source, function (data) {
                result.push(data);
            });

            return result;
        },

        /**
         * Process union insert data.
         *
         * @param {Array} data
         */
        processingUnionInsertData: function (data) {
            var dataCount,
                elemsCount,
                tmpData,
                path,
                attributeCodes = this.source.get('data.attribute_codes');

            this.isEmpty(data.length === 0);
            this.isShowAddProductButton(
                (!attributeCodes || data.length > 0 ? data.length : attributeCodes.length) > 0
            );

            tmpData = data.slice(this.pageSize * (this.currentPage() - 1),
                                 this.pageSize * (this.currentPage() - 1) + this.pageSize);

            this.source.set(this.dataScope + '.' + this.index, []);

            _.each(tmpData, function (row, index) {
                path = this.dataScope + '.' + this.index + '.' + (this.startIndex + index);
                this.source.set(path, row);
            }, this);

            this.source.set(this.dataScope + '.' + this.index, data);
            this.parsePagesData(data);

            // Render
            dataCount = data.length;
            elemsCount = this.elems().length;

            if (dataCount > elemsCount) {
                this.getChildItems().each(function (elemData, index) {
                    this.addChild(elemData, this.startIndex + index);
                }, this);
            } else {
                for (elemsCount; elemsCount > dataCount; elemsCount--) {
                    this.elems()[elemsCount - 1].destroy();
                }
            }

            this.generateAssociatedProducts();
        },

        /**
         * Parsed data
         *
         * @param {Array} data - array with data
         * about selected records
         */
        processingInsertDataFromGrid: function (data) {
            var changes,
                tmpArray;

            if (!data.length) {
                return;
            }

            tmpArray = this.getUnionInsertData();

            changes = this._checkGridData(data);
            this.cacheGridData = data;

            changes.each(function (changedObject) {
                var mappedData = this.mappingValue(changedObject);

                mappedData[this.canEditField] = 0;
                mappedData[this.newProductField] = 0;
                mappedData.variationKey = this._getVariationKey(changedObject);
                mappedData['configurable_attribute'] = this._getConfigurableAttribute(changedObject);
                tmpArray.push(mappedData);
            }, this);

            // Attributes cannot be changed before regeneration thought wizard
            if (!this.source.get('data.attributes').length) {
                this.source.set('data.attributes', this.attributesTmp);
            }
            this.unionInsertData(tmpArray);
        },

        /**
         * Process changes from grid.
         *
         * @param {Object} data
         */
        processingChangeDataFromGrid: function (data) {
            var tmpArray = this.getUnionInsertData(),
                mappedData = this.mappingValue(data.product);

            mappedData[this.canEditField] = 0;
            mappedData[this.newProductField] = 0;
            mappedData.variationKey = this._getVariationKey(data.product);
            mappedData['configurable_attribute'] = this._getConfigurableAttribute(data.product);
            tmpArray[data.rowIndex] = mappedData;

            this.unionInsertData(tmpArray);
        },

        /**
         * Get variation key.
         *
         * @param {Object} data
         * @returns {String}
         * @private
         */
        _getVariationKey: function (data) {
            var attrCodes = this.source.get('data.attribute_codes'),
                key = [];

            attrCodes.each(function (code) {
                key.push(data[code]);
            });

            return key.sort().join('-');
        },

        /**
         * Get configurable attribute.
         *
         * @param {Object} data
         * @returns {String}
         * @private
         */
        _getConfigurableAttribute: function (data) {
            var attrCodes = this.source.get('data.attribute_codes'),
                confAttrs = {};

            attrCodes.each(function (code) {
                confAttrs[code] = data[code];
            });

            return JSON.stringify(confAttrs);
        },

        /**
         * Process data insertion from wizard
         *
         * @param {Object} data
         */
        processingInsertDataFromWizard: function (data) {
            var tmpArray = [];
            var product = {};

            _.each(data, function (row) {
                var ids=[];
                var images = [];

                // for (var i = 0; i < row.facilityIds.length; i++) {

                //     ids.push(row.facilityIds[i]);
                // }

                // for (var j = 0; j < row.images.length; j++) {

                //     images.push(row.images[j]);
                // }
                

                product = {
                    'id': row.id,
                    'type': row.type,
                    'category': row.category,
                    'price': row.price,
                    'status': row.status,
                    'facilitiesIds': row.facilityIds,
                    'roomNumbers' : row.roomNumbers,
                    'deletedRoomNumbers' : row.deletedRoomNumbers,
                    'images' : row.images,
                    'deletedImage' : row.deletedImage,
                    'excludeDaysCheckIn' : row.excludeDaysCheckIn,
                    'excludeDaysCheckOut' : row.excludeDaysCheckOut,
                    'deletedRoomExcludeDays' : row.deletedRoomExcludeDays,
                    'min_booking_allowed_days' : row.min_booking_allowed_days,
                    'max_booking_allowed_days' : row.max_booking_allowed_days,
                    'description' : row.description

                };

                product[this.canEditField] = 0;
                product[this.newProductField] = row.newProduct;

                tmpArray.push(product);
            }, this);
           // console.log(tmpArray);


            this.unionInsertData(tmpArray);
        },

        /**
         * Remove array items matching condition.
         *
         * @param {Array} data
         * @param {Object} condition
         * @returns {Array}
         */
        unsetArrayItem: function (data, condition) {
            var objs = _.where(data, condition);

            _.each(objs, function (obj) {
                var index = _.indexOf(data, obj);

                if (index > -1) {
                    data.splice(index, 1);
                }
            });

            return data;
        },

        /**
         * Check changed records
         *
         * @param {Array} data - array with records data
         * @returns {Array} Changed records
         */
        _checkGridData: function (data) {
            var cacheLength = this.cacheGridData.length,
                curData = data.length,
                max = cacheLength > curData ? this.cacheGridData : data,
                changes = [],
                obj = {};

            max.each(function (record, index) {
                obj[this.map.id] = record[this.map.id];

                if (!_.where(this.cacheGridData, obj).length) {
                    changes.push(data[index]);
                }
            }, this);

            return changes;
        },

        /**
         * Mapped value
         */
        mappingValue: function (data) {
            var result = {};

            _.each(this.map, function (prop, index) {
                result[index] = data[prop];
            });

            return result;
        },

        /**
         * Toggle actions list.
         *
         * @param {Number} rowIndex
         * @returns {Object} Chainable.
         */
        toggleActionsList: function (rowIndex) {
            var state = false;

            if (rowIndex !== this.actionsListOpened()) {
                state = rowIndex;
            }
            this.actionsListOpened(state);

            return this;
        },

        /**
         * Close action list.
         *
         * @param {Number} rowIndex
         * @returns {Object} Chainable
         */
        closeList: function (rowIndex) {
            if (this.actionsListOpened() === rowIndex) {
                this.actionsListOpened(false);
            }

            return this;
        },

        /**
         * Toggle product status.
         *
         * @param {Number} rowIndex
         */
        toggleStatusProduct: function (rowIndex) {
            var tmpArray = this.getUnionInsertData(),
                status = parseInt(tmpArray[rowIndex].status, 10);

            if (status === 1) {
                tmpArray[rowIndex].status = 2;
            } else {
                tmpArray[rowIndex].status = 1;
            }

            this.unionInsertData(tmpArray);
        },

        /**
         * Remove and set new max position
         */
        removeMaxPosition: function () {
            this.maxPosition = 0;
            this.elems.each(function (record) {
                this.maxPosition < record.position ? this.maxPosition = ~~record.position : false;
            }, this);
        },
    });
});
