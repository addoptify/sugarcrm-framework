<?php

namespace DRI\SugarCRM\Tests\Emailer;

/**
 * @author Emil Kilhage
 */
class MockResponse
{
    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @param bool   $success
     * @param string $errorMessage
     */
    public function __construct($success = true, $errorMessage = null)
    {
        $this->success = $success;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
