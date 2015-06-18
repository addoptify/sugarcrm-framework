<?php

namespace DRI\SugarCRM\Module\Exception;

/**
 * @author Emil Kilhage
 */
class ValidationException extends \SugarApiExceptionRequestMethodFailure
{
    /**
     * @var string
     */
    public $errorLabel = 'custom_validation_failure';

    /**
     * @var string
     */
    public $messageLabel = 'CUSTOM_EXCEPTION_VALIDATION_FAILURE';
}
