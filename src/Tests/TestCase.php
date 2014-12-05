<?php

namespace DRI\SugarCRM\Tests;

use DRI\SugarCRM\Component\Database\TransactionalManager;
use DRI\SugarCRM\Emailer\Transport as EmailerTransport;
use \DRI\SugarCRM\Module\BeanFactory;
use \DRI\SugarCRM\Module\Tests\MockBeanFactory;
use DRI\SugarCRM\Tests\Emailer\MockResponse as MockEmailResponse;
use DRI\SugarCRM\Tests\Emailer\MockTransport as MockEmailTransport;

require_once 'include/api/RestService.php';

/**
 * @author Emil Kilhage
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MockEmailTransport
     */
    protected $emailTransport;

    /**
     * @var \DBManager
     */
    protected $db;

    /**
     * @var \TimeDate
     */
    protected $timeDate;

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

        $this->emailTransport = new MockEmailTransport();
        EmailerTransport::setInstance($this->emailTransport);

        $this->db = \DBManagerFactory::getInstance();
        $this->timeDate = \TimeDate::getInstance();

        if ($this->db instanceof TransactionalManager) {
            $this->db->beginTransaction();
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

        if ($this->db instanceof TransactionalManager) {
            $this->db->rollback();
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
     * @return \RestService
     */
    protected function createRestService()
    {
        $api = new \RestService();
        $api->user = $GLOBALS["current_user"];
        return $api;
    }

    /**
     * @param bool $success
     * @param string $errorMessage
     */
    protected function queueEmailResponse($success = true, $errorMessage = "")
    {
        $this->emailTransport->queueResponse(new MockEmailResponse($success, $errorMessage));
    }

    /**
     * @param $moduleName
     * @param array $fields
     * @param bool $save
     * @return \SugarBean
     */
    protected function createBean($moduleName, array $fields = array(), $save = false)
    {
        return $this->getBeanFactory($moduleName)->create($fields, $save);
    }

    /**
     * @param \SugarBean $bean
     */
    protected function addCreated(\SugarBean $bean)
    {
        $this->getBeanFactory($bean->module_dir)->addCreated($bean);
    }

    /**
     * @param \SugarBean $bean
     */
    protected function saveAndReload(\SugarBean $bean)
    {
        $bean->save();
        $bean->retrieve($bean->id);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Account
     */
    protected function createAccount(array $fields = array(), $save = false)
    {
        return $this->createBean("Accounts", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Contact
     */
    protected function createContact(array $fields = array(), $save = false)
    {
        return $this->createBean("Contacts", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Contact
     */
    protected function createContract(array $fields = array(), $save = false)
    {
        return $this->createBean("Contracts", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Opportunity
     */
    protected function createOpportunity(array $fields = array(), $save = false)
    {
        return $this->createBean("Opportunities", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Call
     */
    protected function createCall(array $fields = array(), $save = false)
    {
        return $this->createBean("Calls", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Meeting
     */
    protected function createMeeting(array $fields = array(), $save = false)
    {
        return $this->createBean("Meetings", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Email
     */
    protected function createEmail(array $fields = array(), $save = false)
    {
        return $this->createBean("Emails", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Lead
     */
    protected function createLead(array $fields = array(), $save = false)
    {
        return $this->createBean("Leads", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Note
     */
    protected function createNote(array $fields = array(), $save = false)
    {
        return $this->createBean("Notes", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \aCase
     */
    protected function createCase(array $fields = array(), $save = false)
    {
        return $this->createBean("Cases", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \User
     */
    protected function createUser(array $fields = array(), $save = false)
    {
        return $this->createBean("Users", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Quote
     */
    protected function createQuote(array $fields = array(), $save = false)
    {
        return $this->createBean("Quotes", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Quote
     */
    protected function createBug(array $fields = array(), $save = false)
    {
        return $this->createBean("Bugs", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Quote
     */
    protected function createDocument(array $fields = array(), $save = false)
    {
        return $this->createBean("Documents", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \ProductBundle
     */
    protected function createProductBundle(array $fields = array(), $save = false)
    {
        return $this->createBean("ProductBundles", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \Product
     */
    protected function createProduct(array $fields = array(), $save = false)
    {
        return $this->createBean("Products", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \ProductTemplate
     */
    protected function createProductTemplate(array $fields = array(), $save = false)
    {
        return $this->createBean("ProductTemplates", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \ProductType
     */
    protected function createProductType(array $fields = array(), $save = false)
    {
        return $this->createBean("ProductTypes", $fields, $save);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \ProductCategory
     */
    protected function createProductCategory(array $fields = array(), $save = false)
    {
        return $this->createBean("ProductCategories", $fields, $save);
    }

}
