<?php
namespace ChillDebug\Helper;

/**
 * Class Filesystem
 * @package ChillDebug\Helper
 */
class Filesystem
{
    /**
     * @param $path
     */
    public static function createDir($path)
    {
        if (file_exists($path)) {
            return;
        }

        if (!mkdir($path)) {
            throw new \RuntimeException('Could not create path ' . $path);
        }
    }

    /**
     * @param $path
     * @param $content
     */
    public static function dump($path, $content)
    {
        if (file_exists($path)) {
            return;
        }

        file_put_contents($path, $content, LOCK_EX);
    }

    /**
     * Check if file exists or have not allowed strings
     *
     * @param $filename
     *
     * @return bool
     */
    public static function isFile($filename)
    {
        if ($filename == '-' ||
            strpos($filename, 'eval()\'d code') !== false ||
            strpos($filename, 'ChillDebug') !== false ||
            strpos($filename, 'composer') !== false ||
            strpos($filename, 'runtime-created function') !== false ||
            strpos($filename, 'runkit created function') !== false ||
            strpos($filename, 'assert code') !== false ||
            strpos($filename, 'regexp code') !== false
        ) {
            return false;
        }

        return true;
    }
}