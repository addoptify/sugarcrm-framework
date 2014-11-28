<?php

namespace DRI\SugarCRM\Tests\Emailer;

use DRI\SugarCRM\Emailer\DriverInterface;

/**
 * @author Emil Kilhage
 */
class MockDriver implements DriverInterface
{

    /**
     * @var MockResponse
     */
    protected $currentResponse;

    /**
     * @return MockResponse
     */
    public function getCurrentResponse()
    {
        return $this->currentResponse;
    }

    /**
     * @param MockResponse $currentResponse
     */
    public function setCurrentResponse(MockResponse $currentResponse)
    {
        $this->currentResponse = $currentResponse;
    }

    /**
     * @param $isHtml
     */
    public function setIsHtml($isHtml)
    {

    }

    /**
     * @param string $address
     * @param string $name
     */
    public function setFrom($address, $name)
    {

    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addTo($address, $name)
    {

    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addCC($address, $name)
    {

    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addBCC($address, $name)
    {

    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addReplyTo($address, $name)
    {

    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {

    }

    /**
     * @param $body
     */
    public function setBody($body)
    {

    }

    /**
     * @return bool
     */
    public function send()
    {
        return $this->currentResponse->getSuccess();
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->currentResponse->getErrorMessage();
    }

    /**
     *
     */
    public function create()
    {

    }
}
