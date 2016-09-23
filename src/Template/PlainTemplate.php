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
     * @param array $informationAsArray
     *
     * @return mixed
     */
    public function dump(array $informationAsArray, $kindOfInformation)
    {
        $functionMapper = $kindOfInformation . 'Mapper';

        if (!method_exists($this, $functionMapper)) {
            throw new \LogicException(
                'The kind of information: ' . $kindOfInformation . ' have not function candidate'
            );
        }

        $report = $this->$functionMapper($informationAsArray);


        Filesystem::dump($this->getFullReportPath('txt'), $report);
    }

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    protected function codeCoverageMapper(array $informationAsArray)
    {
        $report = 'Debug report.' . PHP_EOL;
        $report .= 'Total time: ' . $informationAsArray['total_time_execution'] . PHP_EOL;
        $report .= 'Peak memory usage: ' . $informationAsArray['peak_memory_usage'] . PHP_EOL;
        $report .= 'Trace: ' . PHP_EOL;

        $this->removeCommonInfo($informationAsArray);

        foreach ($informationAsArray as $file => $fileInformation) {
            $report .= 'File: ' . $file . PHP_EOL;
            $report .= 'File coverage: ' . $fileInformation['lines_coverage'] . PHP_EOL;
            unset ($fileInformation['lines_coverage']);
            foreach ($fileInformation as $lineInformation) {
                foreach ($lineInformation as $lineNumber => $singleLineInformation) {
                    $report .= 'Line: ' . $lineNumber;
                    foreach ($singleLineInformation as $infoKey => $infoContent) {
                        $report .= ' | ' . $infoKey . ' => ' . $infoContent;
                    }
                    $report .= PHP_EOL;
                }
            }
        }

        return $report;
    }
}
