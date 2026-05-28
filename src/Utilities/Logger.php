<?php
/**
 * Logger Utility Class
 */

namespace App\Utilities;

class Logger
{
    private static $logPath;

    public static function initialize($path = null)
    {
        self::$logPath = $path ?? LOG_PATH;
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }
    }

    /**
     * Log info message
     */
    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log error message
     */
    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log debug message
     */
    public static function debug($message, $context = [])
    {
        if (APP_DEBUG) {
            self::log('DEBUG', $message, $context);
        }
    }

    /**
     * Log message
     */
    private static function log($level, $message, $context = [])
    {
        self::initialize();

        $timestamp = date('Y-m-d H:i:s');
        $file = self::$logPath . '/' . strtolower($level) . '.log';
        
        $contextStr = '';
        if (!empty($context)) {
            $contextStr = ' | Context: ' . json_encode($context);
        }
        
        $logMessage = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        error_log($logMessage, 3, $file);
    }
}
