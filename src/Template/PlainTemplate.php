<?php
namespace ChillDebug\Template;

use ChillDebug\Helper\Filesystem;

/**
 * Class PlainTemplate
 * @package ChillDebug\Template
 */
class PlainTemplate extends Abstracted
{
    /**
     * @param array $stackTrace
     *
     * @return mixed
     */
    public function dump(array $stackTrace)
    {
        $report = 'Debug report.' . PHP_EOL;
        $report .= 'Total time: ' . $stackTrace['total_time_execution'] . PHP_EOL;
        $report .= 'Peak memory usage: ' . $stackTrace['peak_memory_usage'] . PHP_EOL;
        $report .= 'Trace: ' . PHP_EOL;

        $this->removeCommonInfo($stackTrace);

        foreach ($stackTrace as $file => $lines) {
            $report .= 'File: ' . $file . PHP_EOL;
            foreach ($lines as $linesInfo) {
                foreach ($linesInfo as $lineNumber => $lineInfo) {
                    $report .= 'Line: ' . $lineNumber;
                    foreach ($lineInfo as $infoKey => $infoContent) {
                        $report .= ' | ' . $infoKey . ' => ' . $infoContent;
                    }
                    $report .= PHP_EOL;
                }
            }
        }

        Filesystem::dump($this->getFullReportPath('txt'), $report);
    }
}
