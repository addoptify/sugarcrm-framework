<?php

namespace DRI\SugarCRM\Tests;

/**
 * @author Emil Kilhage
 */
class Bootstrap
{

    /**
     *
     */
    public static function boot()
    {
        self::bootSugar();
        self::initDatabase();
        self::pauseTracker();
        self::initLanguage();
        self::disableLogging();
        self::setDefaultPermissions();
        self::silenceLicenseCheck();
        self::clearJsFiles();
        self::updateCOnfig();
        self::initAdminUser();
    }

    public static function updateCOnfig()
    {
        $focus = new \Administration();
        $focus->retrieveSettings();
        $focus->saveSetting('system', 'adminwizard', 1);
    }

    /**
     *
     */
    public static function bootSugar()
    {
        if (!defined('sugarEntry')) {
            define('sugarEntry', true);
        }

        global $sugar_config;
        global $sugar_flavor;
        global $locale;
        global $db;
        global $beanList;
        global $beanFiles;
        global $moduleList;
        global $modInvisList;
        global $adminOnlyList;
        global $modules_exempt_from_availability_check;

        global $app_list_strings;
        global $app_strings;
        global $mod_strings;

        require_once 'include/entryPoint.php';

        // Scope is messed up due to requiring files within a function
        // We need to explicitly assign these variables to $GLOBALS
        foreach (get_defined_vars() as $key => $val) {
            $GLOBALS[$key] = $val;
        }
    }

    /**
     *
     */
    public static function setDefaultPermissions()
    {
        $GLOBALS['sugar_config']['default_permissions'] = array(
            'dir_mode' => 02770,
            'file_mode' => 0777,
            'chown' => '',
            'chgrp' => '',
        );
    }

    /**
     *
     */
    public static function silenceLicenseCheck()
    {
        $_SESSION['VALIDATION_EXPIRES_IN'] = 'valid';
    }

    /**
     *
     */
    public static function initDatabase()
    {
        $GLOBALS['db'] = \DBManagerFactory::getInstance();
    }

    /**
     *
     */
    public static function pauseTracker()
    {
        \TrackerManager::getInstance()->pause();
    }

    /**
     *
     */
    public static function disableLogging()
    {
        \LoggerManager::getLogger()->setLevel('fatal');
    }

    /**
     *
     */
    public static function initLanguage()
    {
        global $sugar_config, $app_list_strings, $app_strings;
        $current_language = $sugar_config['default_language'];
        $app_list_strings = return_app_list_strings_language($current_language);
        $app_strings = return_application_language($current_language);
    }

    /**
     *
     */
    public static function initAdminUser()
    {
        global $current_user;
        $user = new \User();
        $current_user = $user->getSystemUser();
    }

    /**
     *
     */
    public static function clearJsFiles()
    {
        $GLOBALS['js_version_key'] = 'testrunner';
        require_once('modules/Administration/QuickRepairAndRebuild.php');
        $repair = new \RepairAndClear();
        $repair->module_list = array();
        $repair->show_output = false;
        $repair->clearJsLangFiles();
        $repair->clearJsFiles();
    }

}
