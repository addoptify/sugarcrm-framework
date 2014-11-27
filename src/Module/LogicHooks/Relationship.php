<?php

namespace DRI\SugarCRM\Module\LogicHooks;

/**
 * @author Emil Kilhage
 */
class Relationship
{

    const CASCADE_VARDEF_KEY = "cascade";

    private static $processing = array ();
    private static $enabled = array ("update" => true, "deleted" => true);

    public static function enableCascadeUpdate()
    {
        self::$enabled["update"] = true;
    }

    public static function disableCascadeUpdate()
    {
        self::$enabled["update"] = false;
    }

    public static function enableCascadeDelete()
    {
        self::$enabled["deleted"] = true;
    }

    public static function disableCascadeDelete()
    {
        self::$enabled["deleted"] = false;
    }
 
   /**
     * @param \SugarBean $bean
     */
    public function saveFetchedRow(\SugarBean $bean)
    {
        $bean->fetched_row_before_save = $bean->fetched_row;
    }

   /**
     * @param \SugarBean $bean
     */
    public function cascadeMarkDeletedAfter(\SugarBean $bean)
    {
        if (self::$enabled["deleted"])
        {
            foreach ($this->findCascadeLinksByType($bean, "delete") as $linkDef)
            {
                if ($bean->load_relationship($linkDef["name"]))
                {
                    foreach ($bean->{$linkDef["name"]}->getBeans() as $related)
                    {
                        /** @var \SugarBean $related */
                        $related->mark_deleted($related->id);
                    }
                }
            }
        }
    }

    /**
     * @param \SugarBean $bean
     */
    public function cascadeUpdateAfter(\SugarBean $bean)
    {
        if (self::$enabled["update"])
        {
            foreach ($this->findCascadeLinksByType($bean, "update") as $linkDef)
            {
                $resave = false;
                if (isset($linkDef[self::CASCADE_VARDEF_KEY]["update"]) && is_array($linkDef[self::CASCADE_VARDEF_KEY]["update"]) )
                {
                    foreach ($linkDef[self::CASCADE_VARDEF_KEY]["update"] as $fieldname)
                    {
                        if ($bean->$fieldname != $bean->fetched_row_before_save[$fieldname] )
                        {
                            $resave = true;
                            break;
                        }
                    }
                }
                else
                {
                    $resave = true;
                }

                if ($resave && $bean->load_relationship($linkDef["name"]))
                {
                    foreach ($bean->{$linkDef["name"]}->getBeans() as $related)
                    {
                        if (!isset(self::$processing["update"][$related->module_dir][$related->id]))
                        {
                            /** @var \SugarBean $related */
                            self::$processing["update"][$related->module_dir][$related->id] = true;
                            $related->save();
                            unset(self::$processing["update"][$related->module_dir][$related->id]);
                        }
                    }
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

                    if (in_array($type, $cascade) || array_key_exists($type, $cascade)) {
                        $linkDefs[] = $def;
                    }
                }
            }
        }

        return $linkDefs;
    }

}
