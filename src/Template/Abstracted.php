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
        $debugTime = date('d_m_y_H_i_s');

        return $debugTime . '_report.' . $format;
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
