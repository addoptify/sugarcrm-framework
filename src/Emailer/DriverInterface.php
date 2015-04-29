<?php

namespace DRI\SugarCRM\Emailer;

/**
 * @author Emil Kilhage
 */
interface DriverInterface
{
    /**
     *
     */
    public function create();

    /**
     * @param $isHtml
     */
    public function setIsHtml($isHtml);

    /**
     * @param string $address
     * @param string $name
     */
    public function setFrom($address, $name);

    /**
     * @param string $address
     * @param string $name
     */
    public function addTo($address, $name);

    /**
     * @param string $address
     * @param string $name
     */
    public function addCC($address, $name);

    /**
     * @param string $address
     * @param string $name
     */
    public function addBCC($address, $name);

    /**
     * @param string $address
     * @param string $name
     */
    public function addReplyTo($address, $name);

    /**
     * @param $subject
     */
    public function setSubject($subject);

    /**
     * @param $body
     */
    public function setBody($body);

    /**
     * @param string $path
     * @param string $fileName
     */
    public function addAttachment($path, $fileName);

    /**
     * @throws Exception
     */
    public function send();

    /**
     * @return string
     */
    public function getError();
}
