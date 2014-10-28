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
            throw Exception::unableToLoadRelationshipException($rel_name);
        }

        return $return;
    }

}
