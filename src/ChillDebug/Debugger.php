<?php

namespace ChillDebug;

use ChillDebug\Handler\DebugHandler;
use ChillDebug\Helper\Serializer;
use ChillDebug\Helper\Filesystem;
use ChillDebug\Helper\UserInterface;
use ChillDebug\Template\Factory;
use ChillDebug\Handler\CoverageHandler;

/**
 * Class Debug
 */
class Debugger
{
    /**
     * @var Configuration
     */
    protected $configuration;

    protected $traceFile;
    protected $traceFileTmp;

    private $time;

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

        $this->template  = $templateFactory->getTemplate('html', $configuration);


        Filesystem::createDir($configuration->reportsPath);
        $this->template->setReportPath($configuration->reportsPath);
        preg_match('/0\.(?P<decimal>\d+)/', microtime(), $matches);
        $traceFile = 'trace_file_' . date('Y_m_d_h_i_s_') . $matches['decimal'];

        $this->traceFile = realpath($configuration->reportsPath) . DIRECTORY_SEPARATOR . md5($traceFile).'_'.$traceFile;
        $this->traceFileTmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($traceFile).'_'.$traceFile;
    }

    /**
     * Enable the debugger
     */
    public function enable()
    {
        $this->configXDegub();

        if ($this->configuration->dumpDebugFiles) {
            xdebug_start_trace($this->traceFile);
        } else {
            xdebug_start_trace($this->traceFileTmp);
        }

        xdebug_start_code_coverage( XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE );
    }

    /**
     * Disable the debugger
     */
    public function disable()
    {
        xdebug_stop_trace();

        $coverage = xdebug_get_code_coverage();

        xdebug_stop_code_coverage();

        $traceFile = ($this->configuration->dumpDebugFiles) ? $this->traceFile . '.xt' : $this->traceFileTmp . '.xt';
        $trace = explode(
            PHP_EOL,
            file_get_contents($traceFile)
        );

        $debug = DebugHandler::buildDebug(
            $coverage,
            $trace,
            $this->time
        );

        $this->template->dump($this->traceFile, $debug);
    }

    public function configXDegub()
    {
        $this->time = time();

        ini_set('xdebug.collect_params', 4); // 0 None, 1, Simple, 3 Full, 4 insane full, 5 full, variables as php serialized
        ini_set('xdebug.collect_return', 3); // 0 None, 1, Yes
        ini_set('xdebug.collect_assignments', 2); // 0 None, 1, Yes
        ini_set('xdebug.collect_includes', 2); // 0 None, 1, Yes
        ini_set('xdebug.collect_vars', 2); // 0 None, 1, Yes


        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.coverage_enable', 1);

        ini_set('xdebug.var_display_max_depth', 2);
        ini_set('xdebug.var_display_max_data', 128);

        ini_set('xdebug.show_local_vars', 1);
        ini_set('xdebug.show_mem_delta', 1);

        if (UserInterface::isCLI()) {
            ini_set('xdebug.cli_color', 1);
        }

        ini_set('xdebug.max_nesting_level', 1000);

        ini_set('xdebug.trace_format', 1); // 0 is text, 1 is machine, 2 is html
    }
}