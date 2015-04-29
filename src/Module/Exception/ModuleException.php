<?php

namespace DRI\SugarCRM\Module\Exception;

/**
 * @author Emil Kilhage
 */
class ModuleException extends ValidationException
{
    /**
     * throw new \DRI\SugarCRM\Module\Exception\ModuleException("Accounts", "ERR_CONTACTS_DATE_OF_BIRTH_IN_FUTURE");.
     *
     * @param string $module
     * @param string $messageLabel
     */
    public function __construct($module, $messageLabel = 'CUSTOM_EXCEPTION_VALIDATION_FAILURE')
    {
        parent::__construct($messageLabel);
        $this->setExtraData('module', $module);
    }
}
