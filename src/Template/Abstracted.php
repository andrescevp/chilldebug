<?php
namespace ChillDebug\Template;

/**
 * Class Abstracted
 * @package ChillDebug\Template
 */
abstract class Abstracted
{
    protected $reportPath;

    /**
     * @param mixed $reportPath
     */
    public function setReportPath($reportPath)
    {
        $this->reportPath = $reportPath;
    }

    /**
     * @param $format
     *
     * @return string
     */
    private function getReportFilename($format)
    {
        preg_match('/0\.(?P<decimal>\d+)/', microtime(), $matches);
        $codeCoverageFile = 'code_coverage_' . date('Y_m_d_h_i_s_').$matches['decimal'];

        return $codeCoverageFile  . '.' . $format;
    }

    /**
     * @param $format
     *
     * @return string
     */
    protected function getFullReportPath($format)
    {
        return $this->reportPath . DIRECTORY_SEPARATOR . $this->getReportFilename($format);
    }

    /**
     * @param array $informationAsArray
     */
    protected function removeCommonInfo(array &$informationAsArray)
    {
        unset(
            $informationAsArray['total_time_execution'],
            $informationAsArray['peak_memory_usage'],
            $informationAsArray['lines_coverage']
        );
    }


    /**
     * @param array $stackTrace
     *
     * @return mixed
     */
    abstract public function dump(array $informationAsArray, $kindOfInformation);

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    abstract protected function codeCoverageMapper(array $informationAsArray);
}
