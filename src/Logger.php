<?php

namespace DRI\SugarCRM;

require_once 'include/SugarLogger/SugarLogger.php';

/**
 * @author Emil Kilhage
 */
abstract class Logger extends \SugarLogger
{
    /**
     * @var Logger
     */
    protected static $instance;

    /**
     * @return Logger
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    const LEVEL_DEBUG = 'debug';
    const LEVEL_INFO = 'info';
    const LEVEL_WARN = 'warn';
    const LEVEL_ERROR = 'error';
    const LEVEL_FATAL = 'fatal';
    const LEVEL_OFF = 'off';

    /**
     * @var bool
     */
    protected static $on = true;

    /**
     * @var array
     */
    private static $_levelMapping = array(
        self::LEVEL_DEBUG => 100,
        self::LEVEL_INFO => 70,
        self::LEVEL_WARN => 50,
        self::LEVEL_ERROR => 25,
        self::LEVEL_FATAL => 10,
        self::LEVEL_OFF => 0,
    );

    /**
     * @return array
     */
    public static function getLogLevels()
    {
        return array_keys(self::$_levelMapping);
    }

    /**
     *
     */
    public static function off()
    {
        static::$on = false;
    }

    /**
     *
     */
    public static function on()
    {
        static::$on = true;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return \SugarConfig::getInstance()->get(
            'DRI.logger.level',
            \SugarConfig::getInstance()->get(
                'logger.level',
                self::LEVEL_FATAL
            )
        );
    }

    /**
     * @return string
     */
    abstract public function getFilename();

    /**
     *
     */
    protected function _doInitialization()
    {
        $this->log_dir = $this->getLogDir();

        if (!is_dir($this->log_dir)) {
            sugar_mkdir($this->log_dir, true);
        }

        $this->logfile = $this->getFilename();

        parent::_doInitialization();
    }

    /**
     * @return string
     */
    protected function getLogDir()
    {
        return \SugarConfig::getInstance()->get('DRI.logger.log_dir', dirname(SUGAR_PATH).'/logs/');
    }

    /**
     * @param $level
     * @param $message
     */
    public function log($level, $message)
    {
        if (static::$on && $this->isLevelAvailable($level)) {
            parent::log($level, $message);
        }
    }

    /**
     * @param string $level
     *
     * @return bool
     */
    public function isLevelAvailable($level)
    {
        return self::$_levelMapping[$this->getLevel()] >= self::$_levelMapping[$level];
    }

    /**
     * @param string $message
     */
    public function debug($message)
    {
        $this->log(self::LEVEL_DEBUG, $message);
    }

    /**
     * @param string $message
     */
    public function info($message)
    {
        $this->log(self::LEVEL_INFO, $message);
    }

    /**
     * @param string $message
     */
    public function warn($message)
    {
        $this->log(self::LEVEL_WARN, $message);
    }

    /**
     * @param string $message
     */
    public function error($message)
    {
        $this->log(self::LEVEL_ERROR, $message);
    }

    /**
     * @param string $message
     */
    public function fatal($message)
    {
        $this->log(self::LEVEL_FATAL, $message);
    }
}
