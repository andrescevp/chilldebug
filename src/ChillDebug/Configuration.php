<?php
namespace ChillDebug;

/**
 * Class Configuration
 * @package ChillDebug
 */
class Configuration
{
    /**
     * Place where ocate the reports
     *
     * @var string
     */
    public $reportsPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log';

    public $dumpDebugFiles = true;

    public $generateHtmlView = true;
}