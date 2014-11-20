<?php

namespace DRI\SugarCRM\Module\Exception;

/**
 * @author Emil Kilhage
 */
class ModuleFieldsException extends ValidationException
{

    /**
     * @var string
     */
    public $errorLabel = 'custom_field_validation_failure';

    /**
     * @var string
     */
    public $messageLabel = 'CUSTOM_EXCEPTION_FIELD_VALIDATION_FAILURE';

    /**
     * $messageLabel = "ERR_CONTACTS_DATE_OF_BIRTH_IN_FUTURE";
     * $module = "Contacts";
     * $errors = array (
     *     'name' => array (
     *         'required' => true
     *     ),
     * );
     *
     * throw new \DRI\SugarCRM\Module\Exception\ModuleFieldsException($messageLabel, $errors);
     *
     * @param string $messageLabel
     * @param string $module
     * @param array $errors
     */
    public function __construct(
        $messageLabel = "CUSTOM_EXCEPTION_FIELD_VALIDATION_FAILURE",
        $module,
        array $errors = array ())
    {
        if (is_array($module)) {
            $errors = $module;
            $module = $messageLabel;
            $messageLabel = "CUSTOM_EXCEPTION_FIELD_VALIDATION_FAILURE";
        }

        parent::__construct($messageLabel);
        $this->setExtraData("module", $module);
        $this->setExtraData("validation_errors", $errors);
    }

}
