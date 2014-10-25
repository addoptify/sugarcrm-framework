<?php

namespace DRI\SugarCRM\Framework\Composer;

use Composer\Script\CommandEvent;

/**
 * @author Emil Kilhage
 */
class ScriptHandler
{

    /**
     * @param CommandEvent $event
     */
    public static function installFiles(CommandEvent $event)
    {
        $options = self::getOptions($event);
    }

    /**
     * @param CommandEvent $event
     *
     * @return mixed
     */
    protected static function getOptions(CommandEvent $event)
    {
        $options = $event->getComposer()->getPackage()->getExtra();

        return $options;
    }

}
