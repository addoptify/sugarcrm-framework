<?php

namespace DRI\SugarCRM\Module\Tests;

use DRI\SugarCRM\Module\BeanFactory;

/**
 * @author Emil Kilhage
 */
class MockBeanFactory extends BeanFactory
{
    /**
     * @param $moduleName
     *
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
     * @param \SugarBean $bean
     * @param array      $fields
     */
    protected function populateFields(\SugarBean $bean, array $fields)
    {
        parent::populateFields($bean, $fields);

        if (empty($bean->name)) {
            $bean->name = $bean->getObjectName().'_'.mt_rand();
        }
    }

    /**
     *
     */
    public function removeAllCreated()
    {
        $ids = $this->getCreatedIds();

        if (!empty($ids)) {
            $bean = $this->newBean();
            $query = sprintf(
                "DELETE FROM %s WHERE id IN ('%s')",
                $bean->getTableName(),
                implode("', '", $ids)
            );

            \DBManagerFactory::getInstance()->query($query);

            if ($bean->is_AuditEnabled()) {
                $query = sprintf(
                    "DELETE FROM %s WHERE parent_id IN ('%s')",
                    $bean->get_audit_table_name(),
                    implode("', '", $ids)
                );

                \DBManagerFactory::getInstance()->query($query);
            }

            $this->reset();
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->removeAllCreated();
    }
}
