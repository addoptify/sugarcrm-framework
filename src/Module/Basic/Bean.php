<?php

namespace DRI\SugarCRM\Module\Basic;

use \DRI\SugarCRM\Module\Exception;

/**
 * @author Emil Kilhage
 */
class Bean extends \Basic
{

    /**
     * @param string $rel_name
     *
     * @return \bool
     * @throws Exception
     */
    public function load_relationship($rel_name)
    {
        $return = parent::load_relationship($rel_name);

        if (!$return) {
            // throw Exception::unableToLoadRelationshipException($rel_name);
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->id) || !empty($this->new_with_id);
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function isFieldChanged($fieldName)
    {
        $currentValue = isset($this->{$fieldName}) ? $this->{$fieldName} : null;
        $previousValue = isset($this->fetched_row[$fieldName]) ? $this->fetched_row[$fieldName] : null;
        return $currentValue != $previousValue;
    }

}
