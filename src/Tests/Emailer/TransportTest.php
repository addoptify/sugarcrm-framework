<?php

namespace DRI\SugarCRM\Tests\Emailer;

use DRI\SugarCRM\Emailer\Email;
use DRI\SugarCRM\Emailer\Transport;
use DRI\SugarCRM\Tests\TestCase;

/**
 * @author Emil Kilhage
 */
class TransportTest extends TestCase
{

    /**
     * @var MockTransport
     */
    protected $transport;

    protected function setUp()
    {
        parent::setUp();
        $this->transport = new MockTransport();
        Transport::setInstance($this->transport);
    }

    protected function tearDown()
    {
        parent::tearDown();
        Transport::setInstance(new Transport());
    }

    public function testSuccess()
    {
        $this->transport->queueResponse(new MockResponse(true));
        $this->transport->queueResponse(new MockResponse(true));
        $this->transport->queueResponse(new MockResponse(true));

        $email = new Email();
        $email->addReplyTo("emil@kilhage.com", "Kilhage Emil");
        $email->addTo("emil.kilhage@dri-nordic.com", "fdsfsd");
        $email->setSubject("Test Subject");
        $email->setBody("Test Body");
        $email->send();

        $sentEmails = $this->transport->getSentEmails();

        $this->assertEquals(1, count($sentEmails));

        $email = new Email();
        $email->addReplyTo("emil@kilhage.com", "Kilhage Emil");
        $email->addTo("emil.kilhage@dri-nordic.com", "fdsfsd");
        $email->setSubject("Test Subject");
        $email->setBody("Test Body");
        $email->send();

        $sentEmails = $this->transport->getSentEmails();

        $this->assertEquals(2, count($sentEmails));

        $email = new Email();
        $email->addReplyTo("emil@kilhage.com", "Kilhage Emil");
        $email->addTo("emil.kilhage@dri-nordic.com", "fdsfsd");
        $email->setSubject("Test Subject");
        $email->setBody("Test Body");
        $email->send();

        $sentEmails = $this->transport->getSentEmails();

        $this->assertEquals(3, count($sentEmails));
    }

    public function testError()
    {
        $this->transport->queueResponse(new MockResponse(false, "Error"));

        $this->setExpectedException('DRI\SugarCRM\Emailer\Exception');

        $email = new Email();
        $email->addReplyTo("emil@kilhage.se");
        $email->addTo("emilkilhage@gmail.com", 'fdssffsd');
        $email->addCC("emil.kilhage@dri-nordic.com", 'hgrewwe sd');
        $email->setSubject("Test Subject");
        $email->setBody("Test Body");
        $email->send();
    }

}
