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
     * @param array $stackTrace
     */
    protected function removeCommonInfo(array &$stackTrace)
    {
        unset($stackTrace['total_time_execution'], $stackTrace['peak_memory_usage']);
    }

    /**
     * @param array $stackTrace
     *
     * @return mixed
     */
    abstract public function dump(array $stackTrace);
}
