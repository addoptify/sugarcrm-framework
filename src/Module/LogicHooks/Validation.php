<?php

namespace DRI\SugarCRM\Module\LogicHooks;

use DRI\SugarCRM\Module\Exception;

/**
 * @author Emil Kilhage
 */
class Validation
{
    /**
     * @param \SugarBean $bean
     *
     * @throws Exception\DuplicateFieldSetOnRecordException
     * @throws \SugarQueryException
     */
    public function validateUniqueIndices(\SugarBean $bean)
    {
        $indices = Utils::getUniqueIndices($bean);

        foreach ($indices as $index) {
            $query = new \SugarQuery();
            $query->select('id');
            $query->from($bean);

            foreach ($index['fields'] as $fieldName) {
                $query->where()->equals($fieldName, $bean->$fieldName);
            }

            if (!empty($bean->id)) {
                $query->where()->notEquals('id', $bean->id);
            }

            $existing = $query->execute();

            if (!empty($existing)) {
                $errors = array();

                foreach ($index['fields'] as $fieldName) {
                    $fieldDef = $bean->getFieldDefinition($fieldName);
                    if ($fieldDef['type'] == 'id') {
                        $nameFieldDef = $this->getNameDefByIdField($bean, $fieldName);
                        if (is_array($nameFieldDef)) {
                            $fieldName = $nameFieldDef['name'];
                        }
                    }

                    $errors[$fieldName] = array(
                        'dri.unique_field_index' => true,
                    );
                }

                throw new Exception\DuplicateFieldSetOnRecordException(
                    'ERR_DUPLICATE_RECORD_MESSAGE',
                    $bean->module_dir,
                    $errors
                );
            }
        }
    }

    /**
     * @param \SugarBean $bean
     * @param $fieldName
     *
     * @return bool
     */
    private function getNameDefByIdField(\SugarBean $bean, $fieldName)
    {
        foreach ($bean->getFieldDefinitions() as $def) {
            if (isset($def['id_name']) && $def['id_name'] == $fieldName) {
                return $def;
            }
        }

        return false;
    }
}
