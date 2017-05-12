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
    public $reportsPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log';

    /**
     * Kind of report - check src/Template to know the templates available
     *
     * @var string
     */
    public $template = 'html';
}