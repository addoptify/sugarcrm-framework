<?php

namespace DRI\SugarCRM\Module;

/**
 * @author Emil Kilhage
 */
class Exception extends \SugarApiException
{

    /**
     * @param string $name
     * @return Exception
     */
    public static function unableToLoadRelationshipException($name)
    {
        return new self("Unable to load relationship: $name");
    }

}
