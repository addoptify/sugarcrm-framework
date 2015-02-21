<?php

namespace DRI\SugarCRM\Emailer\Driver;

use DRI\SugarCRM\Emailer\DriverInterface;

/**
 * @author Emil Kilhage
 */
class SugarPHPMailer implements DriverInterface
{

    /**
     * @var \SugarPHPMailer
     */
    private $emailer;

    /**
     *
     */
    public function create()
    {
        $this->emailer = new \SugarPHPMailer();
        $this->emailer->setMailerForSystem();
        $this->emailer->ClearAllRecipients();
    }

    /**
     * @param string $path
     * @param string $fileName
     */
    public function addAttachment($path, $fileName)
    {
        $this->emailer->AddAttachment($path, $fileName);
    }

    /**
     * @param $isHtml
     */
    public function setIsHtml($isHtml)
    {
        $this->emailer->IsHTML($isHtml);
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function setFrom($address, $name)
    {
        $this->emailer->SetFrom($address, $name);
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addTo($address, $name)
    {
        $this->emailer->AddAddress($address, $name);
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->emailer->Subject = $subject;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->emailer->Body = $body;
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addCC($address, $name)
    {
        $this->emailer->AddCC($address, $name);
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addBCC($address, $name)
    {
        $this->emailer->AddBCC($address, $name);
    }

    /**
     * @param string $address
     * @param string $name
     */
    public function addReplyTo($address, $name)
    {
        $this->emailer->AddReplyTo($address, $name);
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \phpmailerException
     */
    public function send()
    {
        $this->emailer->prepForOutbound();

        return $this->emailer->Send() && !$this->emailer->IsError();
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->emailer->ErrorInfo;
    }

}
