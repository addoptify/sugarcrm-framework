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

}
