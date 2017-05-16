<?php

namespace ChillDebug\Template;

use ChillDebug\Helper\Filesystem;
use ChillDebug\Helper\XdebugTraceFileParser;


/**
 * Class HtmlTemplate
 *
 * @package ChillDebug\Template
 */
class HtmlTemplate extends Abstracted
{
    const PROTOCOL_URL = 'phpstorm://open?url=file://%s&line=%d';

    /**
     * @param array $stackTrace
     *
     * @return mixed
     */
    public function dump($fileName)
    {
        $template = file_get_contents(__DIR__ . '/../Resources/templates/html_report.html');

        $coverage = $this->codeCoverageMapper(json_decode(file_get_contents($fileName . '.cvg'), true));
        $server   = $this->serverMapper(json_decode(file_get_contents($fileName . '.svr'), true));
        $trace    = $this->stackTraceMapper(explode(PHP_EOL, file_get_contents($fileName . '.xt')));

        $report = '';

        Filesystem::dump($fileName . '.html', sprintf($template, $fileName, $trace, $coverage, $server));
    }

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    protected function codeCoverageMapper(array $informationAsArray)
    {
        $header = '<div class="alert alert-info">';
        $header .= '<ul class="list-group">';
        $header .= '<li class="list-group-item">Total time: ' . $informationAsArray['total_time_execution'] . '</li>';
        $header .= '<li class="list-group-item">Peak memory usage: ' . $informationAsArray['peak_memory_usage'] . '</li>';
        $header .= '</ul>';
        $header .= '</div>';

        $report = $header;

        $this->removeCommonInfo($informationAsArray);

        $report .= '<table class="table">';

        foreach ($informationAsArray as $file => $fileInformation) {
            $report .= '<tr><td><strong>File:</strong></td><td colspan="2">' . $file . '</td></tr>';
            $report .= '<tr><td><strong>File coverage:</strong></td><td colspan="2">' . $fileInformation['lines_coverage'] . ' % </td></tr>';
            $report .= '<tr><td><strong>File </strong></td><td><strong>Executions</strong></td><td><strong>Code</strong></td></tr>';
            unset ($fileInformation['lines_coverage']);
            foreach ($fileInformation as $lineInformation) {
                foreach ($lineInformation as $lineNumber => $singleLineInformation) {
                    $report .= '<tr><td><a href="' . sprintf(self::PROTOCOL_URL, $file,
                            $lineNumber) . '">' . $file . ':' . $lineNumber . '</a></td>';
                    foreach ($singleLineInformation as $infoKey => $infoContent) {
                        if ($infoKey == 'content') {
                            $report .= '<td><pre class="prettyprint">' . $infoContent . '</pre></td>';
                        } else {
                            $report .= '<td>' . $infoContent . '</td>';
                        }
                    }
                    $report .= '</tr>';
                }
            }
        }
        $report .= '</table>';

        return $report;
    }

    protected function serverMapper(array $informationAsArray)
    {
        $header = '<div class="alert alert-info">';
        $header .= '<ul class="list-group">';
        $header .= '<li class="list-group-item">Time: ' . $informationAsArray['time'] . '</li>';
        $header .= '</ul>';
        $header .= '</div>';
        unset($informationAsArray['time']);
        $report = $header;

        $this->removeCommonInfo($informationAsArray);

        $report .= '<ul class="table">';

        foreach ($informationAsArray as $infoKey => $information) {
            $report .= '<li class="list-group-item">' . ucfirst($infoKey) . '<pre class="prettyprint">' . json_encode($information,
                    JSON_PRETTY_PRINT) . '</pre></li>';
        }
        $report .= '</ul>';

        return $report;
    }

    protected function stackTraceMapper(array $informationAsArray)
    {
        $report = '';

        foreach ($informationAsArray as $infoLine) {
            if (preg_match('/<table.*/', $infoLine)) {
                $infoLineAux = str_replace('class=\'xdebug-trace\' dir=\'ltr\' border=\'1\' cellspacing=\'0\'',
                    'class=\'table\'', $infoLine);
                $report .= $infoLineAux;
            } else {
                $report .= $infoLine;
            }
        }

        return $report;
    }
}