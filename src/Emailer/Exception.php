<?php

namespace DRI\SugarCRM\Emailer;

/**
 * @author Emil Kilhage <emil.kilhage@dri-nordic.com>
 */
class Exception extends \Exception
{

    /**
     * @var Email
     */
    private $email;

    /**
     * @param string $message
     * @param Email $email
     */
    public function __construct($message, Email $email)
    {
        $this->email = $email;
        parent::__construct($message);
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

}
