<?php

namespace DRI\SugarCRM\Tests\Emailer;

use DRI\SugarCRM\Emailer\Email;
use DRI\SugarCRM\Tests\TestCase;

/**
 * @author Emil Kilhage
 */
class EmailTest extends TestCase
{
    public function testAddTo()
    {
        $email = new Email();
        $email->addTo('test1@example.com');
        $email->addTo('test2@example.com', 'Test Name 2');

        $email->addTo(array(
            'test3@example.com',
            'test4@example.com' => 'Test Name 4',
        ));

        $to = $email->getTo();

        $this->assertTrue(isset($to['test1@example.com']));
        $this->assertTrue(isset($to['test2@example.com']));
        $this->assertTrue(isset($to['test3@example.com']));
        $this->assertTrue(isset($to['test4@example.com']));

        $this->assertEquals($to['test1@example.com'], '');
        $this->assertEquals($to['test2@example.com'], 'Test Name 2');
        $this->assertEquals($to['test3@example.com'], '');
        $this->assertEquals($to['test4@example.com'], 'Test Name 4');
    }

    public function testAddFrom()
    {
        $email = new Email();
        $email->setFrom('test1@example.com');

        $this->assertEquals('test1@example.com', $email->getFromAddress());
        $this->assertEquals('', $email->getFromName());

        $email->setFrom('test2@example.com', 'My Name');

        $this->assertEquals('test2@example.com', $email->getFromAddress());
        $this->assertEquals('My Name', $email->getFromName());

        $email->setFrom('test3@example.com');

        $this->assertEquals('test3@example.com', $email->getFromAddress());
        $this->assertEquals('', $email->getFromName());
    }

    public function testAddCC()
    {
        $email = new Email();
        $email->addCC('test1@example.com');
        $email->addCC('test2@example.com', 'Test Name 2');

        $email->addCC(array(
                'test3@example.com',
                'test4@example.com' => 'Test Name 4',
            ));

        $to = $email->getCC();

        $this->assertTrue(isset($to['test1@example.com']));
        $this->assertTrue(isset($to['test2@example.com']));
        $this->assertTrue(isset($to['test3@example.com']));
        $this->assertTrue(isset($to['test4@example.com']));

        $this->assertEquals($to['test1@example.com'], '');
        $this->assertEquals($to['test2@example.com'], 'Test Name 2');
        $this->assertEquals($to['test3@example.com'], '');
        $this->assertEquals($to['test4@example.com'], 'Test Name 4');
    }

    public function testAddBCC()
    {
        $email = new Email();
        $email->addBCC('test1@example.com');
        $email->addBCC('test2@example.com', 'Test Name 2');

        $email->addBCC(array(
                'test3@example.com',
                'test4@example.com' => 'Test Name 4',
            ));

        $to = $email->getBCC();

        $this->assertTrue(isset($to['test1@example.com']));
        $this->assertTrue(isset($to['test2@example.com']));
        $this->assertTrue(isset($to['test3@example.com']));
        $this->assertTrue(isset($to['test4@example.com']));

        $this->assertEquals($to['test1@example.com'], '');
        $this->assertEquals($to['test2@example.com'], 'Test Name 2');
        $this->assertEquals($to['test3@example.com'], '');
        $this->assertEquals($to['test4@example.com'], 'Test Name 4');
    }
}
