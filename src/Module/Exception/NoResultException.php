<?php

namespace DRI\SugarCRM\Module\Exception;

/**
 *
 */
class NoResultException extends QueryException
{
    /**
     * @param string $id
     *
     * @return NoResultException
     */
    public static function idNotFoundException($id)
    {
        return new self("Could not found bean with id '$id'");
    }
}
