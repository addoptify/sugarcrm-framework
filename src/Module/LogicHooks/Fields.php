<?php

namespace DRI\SugarCRM\Module\LogicHooks;

/**
 * @author Emil Kilhage
 */
class Fields
{

    /**
     * @param \SugarBean $bean
     */
    public function unsetUniqueIndicesOnDelete(\SugarBean $bean)
    {
        $indices = Utils::getUniqueIndices($bean);

        $fieldNames = array ();
        foreach ($indices as $index) {
            foreach ($index["fields"] as $fieldName) {
                $def = $bean->getFieldDefinition($fieldName);
                if (isset($def["type"]) && in_array($def["type"], array ("varchar", "int"))) {
                    $fieldNames[$fieldName] = $fieldName;
                }
            }
        }

        if (!empty($fieldNames)) {
            $sets = array ();
            foreach ($fieldNames as $fieldName) {
                if (!empty($bean->{$fieldName})) {
                    $sets[] = "$fieldName = null";
                    $bean->{$fieldName} = null;
                }
            }

            if (!empty($sets)) {
                $sql = "UPDATE {$bean->getTableName()} SET " . implode(", ", $sets);
                \DBManagerFactory::getInstance()->query($sql);
            }
        }
    }

}
