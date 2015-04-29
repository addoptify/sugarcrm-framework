<?php

namespace DRI\SugarCRM\Module\Basic;

use DRI\SugarCRM\Module\Exception;
use DRI\SugarCRM\Module\LogicHooks\Utils as LogicHooksUtils;

/**
 * @author Emil Kilhage
 */
class Bean extends \Basic
{
    /**
     * @param string $rel_name
     *
     * @return \bool
     *
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
        return LogicHooksUtils::isNew($this);
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isFieldChanged($fieldName)
    {
        return LogicHooksUtils::isFieldChanged($this, $fieldName);
    }
}
