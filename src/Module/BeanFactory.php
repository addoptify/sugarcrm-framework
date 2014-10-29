<?php

namespace DRI\SugarCRM\Module;

/**
 * Manages creation of new beans
 *
 * @author Emil Kilhage
 */
class BeanFactory
{

    /**
     * @var BeanFactory[]
     */
    private static $instances = array ();

    /**
     * @param string $moduleName
     * @return BeanFactory
     */
    public static function getInstance($moduleName)
    {
        if (!isset(static::$instances[$moduleName])) {
            static::$instances[$moduleName] = static::factory($moduleName);
        }

        return static::$instances[$moduleName];
    }

    /**
     * @param $moduleName
     * @param BeanFactory $instance
     */
    public static function setInstance($moduleName, BeanFactory $instance)
    {
        static::$instances[$moduleName] = $instance;
    }

    /**
     * @param string $moduleName
     * @return BeanFactory
     */
    public static function factory($moduleName)
    {
        $className = static::getClassName($moduleName);
        return new $className($moduleName);
    }

    /**
     * @param $moduleName
     * @return BeanFactory
     */
    public static function getClassName($moduleName)
    {
        $moduleClassName = "\\$moduleName\\BeanFactory";

        if (class_exists($moduleClassName)) {
            return new $moduleClassName($moduleName);
        }

        return __CLASS__;
    }

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var array
     */
    protected $created = array ();

    /**
     * @param string $moduleName
     */
    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return \SugarBean
     */
    public function newBean()
    {
        return \BeanFactory::newBean($this->moduleName);
    }

    /**
     * @param array $fields
     * @param bool $save
     * @return \SugarBean
     */
    public function create(array $fields = array(), $save = false)
    {
        $bean = $this->newBean();

        $this->populateFields($bean, $fields);

        $this->created[] = $bean;

        if ($save) {
            $bean->save();
        }

        return $bean;
    }

    /**
     * @param \SugarBean $bean
     * @param array $fields
     */
    protected function populateFields(\SugarBean $bean, array $fields)
    {
        $fieldDefinitions = $bean->getFieldDefinitions();

        foreach ($fields as $fieldName => $fieldValue) {
            if (isset($fieldDefinitions[$fieldName])) {
                $bean->{$fieldName} = $fields[$fieldName];
            }
        }

        if (!empty($bean->id)) {
            $bean->new_with_id = true;
        }
    }

    /**
     * @return array
     */
    public function getCreatedIds()
    {
        $ids = array ();

        foreach ($this->getCreated() as $bean) {
            if (!empty($bean->id)) {
                $ids[] = $bean->id;
            }
        }

        return $ids;
    }

    /**
     * @return array
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     *
     */
    public function reset()
    {
        $this->created = array ();
    }

}
