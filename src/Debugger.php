<?php

namespace ChillDebug;

use ChillDebug\Helper\Filesystem;
use ChillDebug\Template\Factory;

/**
 * Class Debug
 */
class Debugger
{
    /**
     * @var \ChillDebug\Configuration
     */
    protected $configuration;
    protected $traceFile;

    /**
     * @var array
     */
    private $rawCoverage;

    private $codeCoverage;

    /**
     * Debugger constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration = null)
    {
        if (!$configuration) {
            $configuration       = new Configuration();
            $this->configuration = $configuration;

        }

        $templateFactory = new Factory();
        $this->template  = $templateFactory->getTemplate($configuration->template);
        Filesystem::createDir($configuration->reportsPath);
        $this->template->setReportPath($configuration->reportsPath);
        preg_match('/0\.(?P<decimal>\d+)/', microtime(), $matches);
        $traceFile = 'trace_file_' . date('Y_m_d_h_i_s_') . $matches['decimal'];

        $this->traceFile = realpath($configuration->reportsPath) . DIRECTORY_SEPARATOR . $traceFile;

        file_put_contents(
            $this->traceFile . '.svr',
            json_encode(
                [
                    'time'   => time(),
                    'server' => $_SERVER,
                    'post'   => $_POST,
                    'get'    => $_GET,
                    'files'  => $_FILES,
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * Enable the debugger
     */
    public function enable()
    {
        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.collect_params', 3); // 0 None, 1, Simple, 3 Full
        ini_set('xdebug.collect_return', 1); // 0 None, 1, Yes
        ini_set('xdebug.var_display_max_depth', 2);
        ini_set('xdebug.var_display_max_data', 128);
        ini_set('xdebug.show_local_vars', 1);
        ini_set('xdebug.show_mem_delta', 1);
        ini_set('xdebug.cli_color', 1);
        ini_set('xdebug.max_nesting_level', 1000);
        ini_set('xdebug.trace_format', 2); // 0 is text, 1 is machine, 2 is html
        xdebug_start_trace($this->traceFile);
        xdebug_start_code_coverage();
    }

    /**
     * Disable the debugger
     */
    public function disable()
    {
        $this->extractCodeCoverage();

        xdebug_stop_trace();
        xdebug_stop_code_coverage();

        file_put_contents(
            $this->traceFile . '.cvg',
            json_encode(
                $this->codeCoverage,
                JSON_PRETTY_PRINT
            )
        );

        $this->template->dump($this->traceFile);
    }

    /**
     * Check if file exists or have not allowed strings
     *
     * @param $filename
     *
     * @return bool
     */
    public function isFile($filename)
    {
        if ($filename == '-' ||
            strpos($filename, 'eval()\'d code') !== false ||
            strpos($filename, 'Debugger') !== false ||
            strpos($filename, 'runtime-created function') !== false ||
            strpos($filename, 'runkit created function') !== false ||
            strpos($filename, 'assert code') !== false ||
            strpos($filename, 'regexp code') !== false
        ) {
            return false;
        }
        return true;
    }

    protected function extractCodeCoverage()
    {
        $this->rawCoverage                          = xdebug_get_code_coverage();
        $this->codeCoverage['total_time_execution'] = xdebug_time_index();
        $this->codeCoverage['peak_memory_usage']    = xdebug_peak_memory_usage();
        foreach ($this->rawCoverage as $file => $lines) {
            if (!$this->isFile($file)) {
                continue;
            }
            $this->codeCoverage[$file] = [];
            $fileAsArray               = file($file);
            $totalAmountFileLines      = count($fileAsArray);
            $linesCovered              = 0;
            foreach ($lines as $line => $executedTimes) {
                $this->codeCoverage[$file]['lines'][$line] = [
                    'executions' => $executedTimes,
                    'content'    => trim($fileAsArray[$line - 1]),
                ];
                $linesCovered++;
            }
            $this->codeCoverage[$file]['lines_coverage'] = (($linesCovered / $totalAmountFileLines) * 100);
        }
    }
}