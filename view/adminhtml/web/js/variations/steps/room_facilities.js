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
            modules: {
                // multiselect: '${ $.multiselectName }',
                // attributeProvider: '${ $.providerName }'
            },
            listens: {
                // '${ $.multiselectName }:selected': 'doSelectedAttributesLabels',
                // '${ $.multiselectName }:rows': 'doSelectSavedFacilities'
            },
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
           //console.log(this._super().observe(['selectedAttributes']));

            return this;
        },
        render: function (wizard) {
            this.wizard = wizard;

            //this.setNotificationMessage();
        },
        // setNotificationMessage: function () {
        //     if (this.mode === 'edit') {
        //         this.wizard.setNotificationMessage($.mage.__('When you remove or add an attribute, we automatically ' +
        //         'update all configurations and you will need to recreate current configurations manually.'));
        //     }
        // },
        doSelectSavedFacilities: function () {

            if (this.stepInitialized === false) {
                this.stepInitialized = true;
                //cache attributes labels, which can be present on the 2nd page
                _.each(this.initData.facilities, function (facilities) {
                    this.attributesLabels[facilities.id] = facilities.title;
                }.bind(this));
                this.multiselect().selected(_.pluck(this.initData.facilities, 'id'));
            }
        },
        // doSelectedAttributesLabels: function (selected) {
        //     var labels = [];

        //     this.selected = selected;
        //     _.each(selected, function (attributeId) {
        //         var attribute;

        //         if (!this.attributesLabels[attributeId]) {
        //             attribute = _.findWhere(this.multiselect().rows(), {
        //                 attribute_id: attributeId
        //             });

        //             if (attribute) {
        //                 this.attributesLabels[attribute.attribute_id] = attribute.frontend_label;
        //             }
        //         }
        //         labels.push(this.attributesLabels[attributeId]);
        //     }.bind(this));
        //     this.selectedAttributes(labels.join(', '));
        // },
        force: function (wizard) {
           // wizard.data.attributesIds = this.multiselect().selected;
           // console.log(wizard.data.attributesIds);

           // console.log(wizard.data.attributesIds);

            // if (!wizard.data.attributesIds() || wizard.data.attributesIds().length === 0) {
            //     throw new Error($.mage.__('Please select attribute(s).'));
            // }
            //this.setNotificationMessage();
        },
        back: function () {
        }
    });
});
