<?php
namespace ChillDebug;

use ChillDebug\Helper\Filesystem;
use ChillDebug\Template\Factory;

/**
 * Class Debug
 */
class Debugger
{
    private $rawCoverage;

    private $stackTraceInfo;

    /**
     * Debugger constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration = null)
    {
        if (!$configuration) {
            $configuration = new Configuration();
        }

        $templateFactory = new Factory();
        $this->template = $templateFactory->getTemplate($configuration->template);
        Filesystem::createDir($configuration->reportsPath);
        $this->template->setReportPath($configuration->reportsPath);
    }

    /**
     * Enable the debugger
     */
    public function enable()
    {
        xdebug_start_code_coverage();
    }

    /**
     * Disable the debugger
     */
    public function disable()
    {
        xdebug_stop_code_coverage();
    }

    /**
     * Get and dump the report in the location given in the configuration
     */
    public function getStackTrace()
    {
        $this->rawCoverage = xdebug_get_code_coverage();
        ini_set('xdebug.collect_params', 3);
        $this->stackTraceInfo['total_time_execution'] = xdebug_time_index();
        $this->stackTraceInfo['peak_memory_usage'] = xdebug_peak_memory_usage();
        foreach ($this->rawCoverage as $file => $lines) {
            if (preg_match('/.Debugger./', $file)) {
                continue;
            }

            $this->stackTraceInfo[$file] = [];
            foreach ($lines as $line => $executedTimes) {
                $fileAsArray = file($file);
                $this->stackTraceInfo[$file]['lines'][$line] = [
                    'executions' => $executedTimes,
                    'content' => trim($fileAsArray[$line - 1])
                ];
            }
        }

        $this->template->dump($this->stackTraceInfo);
    }
}