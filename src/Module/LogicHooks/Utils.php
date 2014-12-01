<?php

namespace DRI\SugarCRM\Module\LogicHooks;

/**
 * @author Emil Kilhage
 */
class Utils
{

    /**
     * Safe method to check if a bean in its current
     * state is new and will be created upon save
     *
     * @param \SugarBean $bean
     * @return bool
     */
    public static function isNew(\SugarBean $bean)
    {
        return empty($bean->id) || !empty($bean->new_with_id);
    }

    /**
     * Safe method for checking if a field on a bean has been changed
     *
     * @param \SugarBean $bean
     * @param string $fieldName
     * @return bool
     */
    public static function isFieldChanged(\SugarBean $bean, $fieldName)
    {
        $currentValue = isset($bean->{$fieldName}) ? $bean->{$fieldName} : null;
        $previousValue = isset($bean->fetched_row[$fieldName]) ? $bean->fetched_row[$fieldName] : null;
        return $currentValue != $previousValue;
    }

}