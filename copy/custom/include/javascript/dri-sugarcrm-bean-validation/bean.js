/**
 * @author Emil Kilhage
 */
(function (app) {

    var oldSave = app.Bean.prototype.save;
    var oldDestroy = app.Bean.prototype.destroy;
    _.extend(app.Bean.prototype, {

        /**
         * @param attributes
         * @param options
         * @returns {*}
         */
        save: function (attributes, options) {
            var self = this;
            if (!_.isObject(options)) {
                options = {};
            }

            var oldErrorHandler = options.error;
            options.error = function (error) {
                if (oldErrorHandler) {
                    oldErrorHandler.apply(this, arguments);
                }

                self.customValidationErrors(error, false);
            };

            return oldSave.call(this, attributes, options);
        },

        /**
         * @param options
         * @returns {*}
         */
        destroy: function (options) {
            var self = this;
            if (!_.isObject(options)) {
                options = {};
            }

            var oldErrorHandler = options.error;
            options.error = function (error) {
                if (oldErrorHandler) {
                    oldErrorHandler.apply(this, arguments);
                }

                self.customValidationErrors(error, false);
            };

            return oldDestroy.call(this, options);
        },

        /**
         * @param {object} error
         * @param {boolean} skipGeneralErrors
         */
        customValidationErrors: function (error, skipGeneralErrors) {
            var id;
            if (!error || !error.payload) {
                return;
            }

            switch (error.payload.error) {
                case "custom_validation_failure":
                    if (!skipGeneralErrors) {
                        id = [
                            "custom_validation_failure",
                            error.payload.module
                        ].join(":");

                        app.alert.dismissAll();

                        app.alert.show(id, {
                            level: "error",
                            messages: error.payload.error_message,
                            autoClose: true,
                            autoCloseDelay: 8000
                        });
                    }
                    break;
                case "custom_field_validation_failure":
                    if (_.isObject(error.payload.validation_errors) &&
                        error.payload.module === this.module) {

                        id = [
                            "custom_validation_failure",
                            error.payload.module
                        ].join(":");

                        this._processValidationErrors(error.payload.validation_errors);

                        app.alert.dismissAll();

                        app.alert.show(id, {
                            level: "error",
                            messages: error.payload.error_message,
                            autoClose: true,
                            autoCloseDelay: 8000
                        });
                    }

                    break;
            }
        }

    });

}(SUGAR.App));
