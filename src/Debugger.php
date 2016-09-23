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
    public function gerCodeCoverageInformation()
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
            $fileAsArray = file($file);
            $totalAmountFileLines = count($fileAsArray);
            $linesCovered = 0;
            foreach ($lines as $line => $executedTimes) {
                $this->stackTraceInfo[$file]['lines'][$line] = [
                    'executions' => $executedTimes,
                    'content' => trim($fileAsArray[$line - 1])
                ];
                $linesCovered++;
            }
            $this->stackTraceInfo[$file]['lines_coverage'] = (($linesCovered/$totalAmountFileLines) * 100);
        }

        $this->template->dump($this->stackTraceInfo, 'codeCoverage');
    }
}