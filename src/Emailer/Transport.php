<?php

namespace DRI\SugarCRM\Emailer;

require_once 'include/SugarPHPMailer.php';
require_once 'modules/Administration/Administration.php';

/**
 * @author Emil Kilhage <emil.kilhage@dri-nordic.com>
 */
class Transport
{

    /**
     * @var Transport
     */
    private static $instance;

    /**
     * @return Transport
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * @param Transport $instance
     */
    public static function setInstance(Transport $instance)
    {
        self::$instance = $instance;
    }

    /**
     * @var array
     */
    protected $sent_emails = array ();

    /**
     * @var
     */
    protected $logger;

    /**
     * @var string
     */
    protected $driverClass = '\DRI\SugarCRM\Emailer\Driver\SugarPHPMailer';

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        if (is_null($this->driver)) {
            $driverClass = $this->getDriverClass();
            $this->driver = new $driverClass();
        }

        return $this->driver;
    }

    /**
     * @return string
     */
    public function getDriverClass()
    {
        return $this->driverClass;
    }

    /**
     * @param string $driverClass
     */
    public function setDriverClass($driverClass)
    {
        $this->driverClass = $driverClass;
    }

    /**
     * @return null
     */
    public function getLogger()
    {
        if (is_null($this->logger)) {
            return \LoggerManager::getLogger();
        }

        return $this->logger;
    }

    protected function create()
    {
        $emailer = $this->getDriver();
        $emailer->create();
        return $emailer;
    }

    /**
     * @param Email $email
     * @return Email
     * @throws Exception
     */
    public function send(Email $email)
    {
        $logger = $this->getLogger();

        try {
            $this->sent_emails[] = $email;

            $emailer = $this->create();

            $emailer->setIsHtml($email->isHtml());

            $fromAddress = $email->getFromAddress();

            if (empty($fromAddress)) {
                $administration = new \Administration();
                $settings = $administration->retrieveSettings();
                $email->setFromAddress($settings->settings['notify_fromaddress']);
                $email->setFromName($settings->settings['notify_fromname']);
            }

            $emailer->setFrom($email->getFromAddress(), $email->getFromName());

            $logger->info("Sending Email from {$email->getFromAddress()}, {$email->getFromAddress()}");

            foreach ($email->getTo() as $address => $name) {
                $logger->info("Adding To {$address}, {$name}");
                $emailer->addTo($address, $name);
            }

            foreach ($email->getCC() as $address => $name) {
                $logger->info("Adding CC {$address}, {$name}");
                $emailer->addCC($address, $name);
            }

            foreach ($email->getBCC() as $address => $name) {
                $logger->info("Adding BCC {$address}, {$name}");
                $emailer->addBCC($address, $name);
            }

            foreach ($email->getReplyTo() as $address => $name) {
                $logger->info("Adding ReplyTo {$address}, {$name}");
                $emailer->addReplyTo($address, $name);
            }

            foreach ($email->getAttachments() as $attachment) {
                $emailer->addAttachment($attachment["path"], $attachment["fileName"]);
            }

            $emailer->setSubject($email->getSubject());
            $emailer->setBody($email->getBody());

            $logger->debug("Subject: '{$email->getSubject()}'");
            $logger->debug("Body: '{$email->getBody()}'");

            $logger->info("Sending Email");

            if (!$emailer->send()) {
                $this->throwError($email, $emailer->getError());
            }

            return $email;
        } catch (Exception $e) {
            $logger->fatal($e);
            throw $e;
        }
    }

    /**
     * @param Email $email
     * @param $errorInfo
     *
     * @throws Exception
     */
    protected function throwError(Email $email, $errorInfo)
    {
        $to = print_r($email->getTo(), true);
        throw new Exception("$errorInfo", $email);
    }

    /**
     * @return array
     */
    public function getSentEmails()
    {
        return $this->sent_emails;
    }

}
