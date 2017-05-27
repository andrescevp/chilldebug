<?php
namespace ChillDebug\Template;

use ChillDebug\Configuration;
use ChillDebug\Dto\CoverageDto;
use ChillDebug\Dto\DebugDto;
use ChillDebug\Dto\RequestDto;
use ChillDebug\Dto\TraceDto;
use ChillDebug\Handler\CoverageHandler;
use ChillDebug\Handler\RequestHandler;
use ChillDebug\Helper\Filesystem;
use ChillDebug\Helper\UserInterface;

/**
 * Class Abstracted
 * @package ChillDebug\Template
 */
abstract class Abstracted
{
    protected $reportPath;

    protected $config;

    public function __construct(Configuration $configuration = null)
    {
        if (!$configuration) {
            $configuration = new Configuration();
        }

        $this->config = $configuration;
    }

    /**
     * @param DebugDto $debugDto
     * @return array
     */
    public function getGeneralInformation(DebugDto $debugDto)
    {
        $isCli = UserInterface::isCLI();
        $serverData = $debugDto->getRequest()->getServer();
        $getData = $debugDto->getRequest()->getGet();

        if ($isCli) {
            $type = 'CLI';
            $request = implode(' ', $serverData['argv']);
        } else {
            $type = 'REQUEST';
            $request = $serverData['HTTP_HOST'] . $serverData['REQUEST_URI'] . '?' . http_build_query($getData);
        }

        return array($type, $request);
    }

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
        $codeCoverageFile = 'full_report_' . date('Y_m_d_h_i_s_').$matches['decimal'];

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
     */
    public function dump($fileName, DebugDto $debugDto) {
        if ($this->config->dumpDebugFiles) {
            Filesystem::dump($fileName . '.srv', json_encode(
                    RequestHandler::getRequest(md5(serialize($debugDto->getRequest()))),
                    JSON_PRETTY_PRINT)
            );

            Filesystem::dump($fileName . '.cvg', json_encode(
                    CoverageHandler::getCoverageRaw(md5(serialize($debugDto->getCoverage()))),
                    JSON_PRETTY_PRINT)
            );
        }
    }

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    abstract protected function codeCoverageMapper(CoverageDto $coverageDto);

    /**
     * @param RequestDto $requestDto
     * @return mixed
     */
    abstract protected function serverMapper(RequestDto $requestDto);

    /**
     * @param TraceDto $traceDto
     * @return mixed
     */
    abstract protected function stackTraceMapper(TraceDto $traceDto);

}
