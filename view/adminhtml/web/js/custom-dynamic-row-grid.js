/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'Magento_Ui/js/dynamic-rows/dynamic-rows'
], function (_, dynamicRows) {
    'use strict';

    return dynamicRows.extend({

        /**
         * Synchronizes multiple elements arrays with a core '_elems' container.
         * Performs elemets grouping by theirs 'displayArea' property.
         * @private
         *
         * @returns {Collection} Chainable.
         */
        _updateCollection: function () {
            this._super();
            this._sort();
            return this;
        },

        /**
         * Delete record
         *
         * @param {Number} index - row index
         *
         */
        deleteRecord: function (index, recordId) {
            var recordInstance,
                lastRecord,
                recordsData,
                lastRecordIndex;

            if (this.deleteProperty) {
                recordsData = this.recordData();
                recordInstance = _.find(this.elems(), function (elem) {
                    return elem.index === index;
                });
                recordInstance.destroy();
                this.elems([]);
                this._updateCollection();
                this.removeMaxPosition();
                recordsData[recordInstance.index][this.deleteProperty] = this.deleteValue;
                this.recordData(recordsData);
                //this.reinitRecordData();
                //this.reload();
                this._sort();
            } else {
                this.update = true;

                if (~~this.currentPage() === this.pages()) {
                    lastRecordIndex = this.startIndex + this.getChildItems().length - 1;
                    lastRecord =
                        _.findWhere(this.elems(), {
                            index: lastRecordIndex
                        }) ||
                        _.findWhere(this.elems(), {
                            index: lastRecordIndex.toString()
                        });

                    lastRecord.destroy();
                }

                this.removeMaxPosition();
                recordsData = this._getDataByProp(recordId);
                this._updateData(recordsData);
                this.update = false;
            }

            this._reducePages();
            this._sort();
        }
    });
});
