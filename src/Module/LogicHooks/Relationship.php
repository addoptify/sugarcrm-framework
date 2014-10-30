<?php

namespace DRI\SugarCRM\Module\LogicHooks;

/**
 * @author Emil Kilhage
 */
class Relationship
{

    const CASCADE_VARDEF_KEY = "dri_cascade";

    /**
     * @param \SugarBean $bean
     */
    public function cascadeMarkDeletedAfter(\SugarBean $bean)
    {
        foreach ($this->findCascadeLinksByType($bean, "delete") as $linkDef) {
            if ($bean->load_relationship($linkDef["name"])) {
                foreach ($bean->{$linkDef["name"]}->getBeans() as $related) {
                    /** @var \SugarBean $related */
                    $related->mark_deleted($related->id);
                }
            }
        }
    }

    /**
     * @param \SugarBean $bean
     */
    public function cascadeUpdateAfter(\SugarBean $bean)
    {
        foreach ($this->findCascadeLinksByType($bean, "update") as $linkDef) {
            if ($bean->load_relationship($linkDef["name"])) {
                foreach ($bean->{$linkDef["name"]}->getBeans() as $related) {
                    /** @var \SugarBean $related */
                    $related->save();
                }
            }
        }
    }

    /**
     * @param \SugarBean $bean
     * @param $type
     *
     * @return array
     */
    private function findCascadeLinksByType(\SugarBean $bean, $type)
    {
        $linkDefs = array ();

        foreach ($bean->getFieldDefinitions() as $def) {
            if (isset($def["type"]) && $def["type"] == "link") {
                if (isset($def[self::CASCADE_VARDEF_KEY])) {
                    $cascade = $def[self::CASCADE_VARDEF_KEY];
                    if (is_string($cascade)) {
                        $cascade = array ($cascade);
                    } else if (!is_array($cascade)) {
                        throw new \InvalidArgumentException();
                    }

                    if (in_array($type, $cascade)) {
                        $linkDefs[] = $def;
                    }
                }
            }
        }

        return $linkDefs;
    }

}
