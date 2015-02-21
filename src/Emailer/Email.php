<?php

namespace DRI\SugarCRM\Emailer;

/**
 * $email = new \DRI\SugarCRM\Emailer\Email();
 *
 * $email->setSubject($subject);
 * $email->setBody($body);
 *
 * $email->addTo("emil.kilhage@dri-nordic.com");
 *
 * $email->addCC(array (
 *     "emil.kilhage@dri-nordic.com",
 *     "emil.kilhage@dri-nordic.com" => "Emil Kilhage", // Provide the name
 * ));
 *
 * $email->addCC("emil@dri-nordic.com", "Emil Kilhage"); // Add only one CC address with name
 * $email->addCC("kilhage@dri-nordic.com"); // Add only one CC address without name
 *
 * // addTo, addCC and addBCC have the same method signatures
 *
 * try {
 *     $email->send();
 * } catch (\DRI\SugarCRM\Emailer\Exception $e) {
 *     // Handle errors
 * }
 *
 * @author Emil Kilhage <emil.kilhage@dri-nordic.com>
 */
class Email
{

    /**
     * @var string
     */
    protected $fromAddress;

    /**
     * @var string
     */
    protected $fromName;

    /**
     * @var array
     */
    protected $to = array ();

    /**
     * @var array
     */
    protected $cc = array ();

    /**
     * @var array
     */
    protected $bcc = array ();

    /**
     * @var array
     */
    protected $replyTo = array ();

    /**
     * @var array
     */
    protected $attachments = array ();

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var bool
     */
    protected $isHtml = true;

    /**
     * @param $path
     * @param $fileName
     */
    public function addAttachment($path, $fileName)
    {
        $this->attachments[] = array (
            "path" => $path,
            "fileName" => $fileName,
        );
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return boolean
     */
    public function isHtml()
    {
        return $this->isHtml;
    }

    /**
     * @param boolean $isHtml
     */
    public function setIsHtml($isHtml)
    {
        $this->isHtml = $isHtml;
    }

    /**
     * @return Transport
     */
    public function getTransport()
    {
        return Transport::getInstance();
    }

    /**
     * @return array
     */
    public function getBCC()
    {
        return $this->bcc;
    }

    /**
     * @param string $address
     * @param string|null $name
     */
    public function addBCC($address, $name = "")
    {
        if (is_array($address)) {
            foreach ($address as $emailAddress => $name) {
                if (is_int($emailAddress)) {
                    $emailAddress = $name;
                    $name = "";
                }

                $this->bcc[$emailAddress] = $name;
            }
        } else {
            $this->bcc[$address] = $name;
        }
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return array|string
     */
    public function getCC()
    {
        return $this->cc;
    }

    /**
     * @param string $address
     * @param string|null $name
     */
    public function addCC($address, $name = "")
    {
        if (is_array($address)) {
            foreach ($address as $emailAddress => $name) {
                if (is_int($emailAddress)) {
                    $emailAddress = $name;
                    $name = "";
                }

                $this->cc[$emailAddress] = $name;
            }
        } else {
            $this->cc[$address] = $name;
        }
    }

    /**
     * @param string $address
     * @param string|null $name
     */
    public function setFrom($address, $name = "")
    {
        $this->setFromName($name);
        $this->setFromAddress($address);
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param string $from_address
     */
    public function setFromAddress($from_address)
    {
        $this->fromAddress = $from_address;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param string $from_name
     */
    public function setFromName($from_name)
    {
        $this->fromName = $from_name;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return array|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return array|string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string|array $address
     * @param string|null $name
     */
    public function addTo($address, $name = "")
    {
        if (is_array($address)) {
            foreach ($address as $emailAddress => $name) {
                if (is_int($emailAddress)) {
                    $emailAddress = $name;
                    $name = "";
                }

                $this->to[$emailAddress] = $name;
            }
        } else {
            $this->to[$address] = $name;
        }
    }

    /**
     * @param string|array $address
     * @param string|null $name
     */
    public function addReplyTo($address, $name = "")
    {
        if (is_array($address)) {
            foreach ($address as $emailAddress => $name) {
                if (is_int($emailAddress)) {
                    $emailAddress = $name;
                    $name = "";
                }

                $this->replyTo[$emailAddress] = $name;
            }
        } else {
            $this->replyTo[$address] = $name;
        }
    }

    /**
     * @return Email
     * @throws Exception
     * @throws \Exception
     */
    public function send()
    {
        return $this->getTransport()->send($this);
    }

}
