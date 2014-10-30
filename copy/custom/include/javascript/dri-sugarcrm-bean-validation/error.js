/**
 * @author Emil Kilhage
 */
(function (app) {

    /**
     * Custom validation errors
     */
    _.extend(app.error.errorName2Keys, {

        /**
         * @type {string}
         */
        "dri.unique_field_index": "ERR_VALIDATION_UNIQUE_FIELD_INDEX_FIELD_MESSAGE"

    });

}(SUGAR.App));
