<?php

namespace DRI\SugarCRM\Tests;

use \DRI\SugarCRM\Module\BeanFactory;
use \DRI\SugarCRM\Module\Tests\MockBeanFactory;;

/**
 * @author Emil Kilhage
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        global $beanList;
        parent::setUp();

        foreach ($beanList as $moduleName => $object_name) {
            $this->setUpBeanFactory($moduleName);
        }
    }

    /**
     * @param $moduleName
     */
    private function setUpBeanFactory($moduleName)
    {
        $instance = MockBeanFactory::factory($moduleName);
        BeanFactory::setInstance($moduleName, $instance);
    }

    /**
     *
     */
    protected function tearDown()
    {
        global $beanList;
        parent::tearDown();

        foreach ($beanList as $moduleName => $object_name) {
            $this->tearDownBeanFactory($moduleName);
        }
    }

    /**
     * @param $moduleName
     */
    private function tearDownBeanFactory($moduleName)
    {
        $instance = BeanFactory::factory($moduleName);
        BeanFactory::setInstance($moduleName, $instance);
    }

    /**
     * @param string $moduleName
     * @return BeanFactory
     */
    public function getBeanFactory($moduleName)
    {
        return BeanFactory::getInstance($moduleName);
    }

    /**
     * @param $moduleName
     * @param array $fields
     * @param bool $save
     * @return \SugarBean
     */
    public function createBean($moduleName, array $fields = array(), $save = false)
    {
        return $this->getBeanFactory($moduleName)->create($fields, $save);
    }

    /**
     * @param \SugarBean $bean
     */
    public function saveAndReload(\SugarBean $bean)
    {
        $bean->save();
        $bean->retrieve($bean->id);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Account
     */
    public function createAccount(array $fields = array(), $save = false)
    {
        return $this->createBean("Accounts", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Contact
     */
    public function createContact(array $fields = array(), $save = false)
    {
        return $this->createBean("Contacts", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Opportunity
     */
    public function createOpportunity(array $fields = array(), $save = false)
    {
        return $this->createBean("Opportunities", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Call
     */
    public function createCall(array $fields = array(), $save = false)
    {
        return $this->createBean("Calls", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Meeting
     */
    public function createMeeting(array $fields = array(), $save = false)
    {
        return $this->createBean("Meetings", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Email
     */
    public function createEmail(array $fields = array(), $save = false)
    {
        return $this->createBean("Emails", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Lead
     */
    public function createLead(array $fields = array(), $save = false)
    {
        return $this->createBean("Leads", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Note
     */
    public function createNote(array $fields = array(), $save = false)
    {
        return $this->createBean("Notes", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \aCase
     */
    public function createCase(array $fields = array(), $save = false)
    {
        return $this->createBean("Cases", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \User
     */
    public function createUser(array $fields = array(), $save = false)
    {
        return $this->createBean("Users", $fields, $save);
    }

}
