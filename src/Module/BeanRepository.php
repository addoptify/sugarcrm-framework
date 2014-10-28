<?php

namespace DRI\SugarCRM\Module;

/**
 * An EntityRepository serves as a repository for entities with
 * generic as well as business specific methods for retrieving entities.
 *
 * This class is designed for inheritance and users can subclass this class
 * to write their own repositories with business-specific methods to locate entities.
 *
 * Heavily inspired by the Doctrine EntityRepository
 * https://github.com/doctrine/doctrine2
 *
 * @author Emil Kilhage
 */
class BeanRepository
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
        $moduleClassName = "\\$moduleName\\BeanRepository";

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
     * @param string $moduleName
     */
    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Retrieves a bean with id $id and
     * returns a instance of the retrieved bean
     *
     * @param string $id: the id of the bean that should be retrieved
     *
     * @return \SugarBean
     * @throws Exception\NoResultException
     */
    public function find($id)
    {
        $bean = $this->newBean();
        $bean->retrieve($id);

        if (empty($bean->id)) {
            throw Exception\NoResultException::idNotFoundException($id);
        }

        return $bean;
    }

    /**
     * Retrieves a bean with name $name and
     * returns a instance of the retrieved bean
     *
     * @param string $name: the name of the bean that should be retrieved
     * @return \SugarBean
     * @throws Exception\NoResultException
     * @throws Exception\NonUniqueResultException
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array ("name" => $name));
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        return $this->findBy(array());
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $query = $this->createQuery("id", $criteria, $orderBy, $limit, $offset);

        $results = $query->execute();
        $beans = array ();

        foreach ($results as $row) {
            try {
                $beans[] = $this->find($row["id"]);
            } catch (Exception\NoResultException $e) {

            }
        }

        return $beans;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return \SugarBean
     * @throws Exception\NoResultException
     * @throws Exception\NonUniqueResultException
     */
    public function findOneBy(array $criteria, array $orderBy = array ())
    {
        $query = $this->createQuery("id", $criteria, $orderBy);

        $results = $query->execute();

        if (empty($results)) {
            throw new Exception\NoResultException("No Results Found");
        }

        if (count($results) > 1) {
            throw new Exception\NonUniqueResultException("Non Unique Result");
        }

        return $this->find($results[0]["id"]);
    }

    /**
     * @param null|string|array $select
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     *
     * @return \SugarQuery
     * @throws \SugarQueryException
     */
    protected function createQuery(
        $select = null,
        array $criteria = null,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $query = new \SugarQuery();
        $query->from($this->newBean());

        if (isset($select)) {
            $query->select($select);
        }

        $where = $query->where();

        if (isset($orderBy)) {
            foreach ($criteria as $field => $value) {
                $where->equals($field, $value);
            }
        }

        if (isset($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        if (isset($limit)) {
            $query->limit($limit);
        }

        if (isset($offset)) {
            $query->offset($offset);
        }

        return $query;
    }

    /**
     * @return \SugarBean
     */
    protected function newBean()
    {
        return $this->getBeanFactory()->newBean();
    }

    /**
     * @return BeanFactory
     */
    protected function getBeanFactory()
    {
        return BeanFactory::getInstance($this->getModuleName());
    }

}
