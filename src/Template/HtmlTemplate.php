<?php

namespace ChillDebug\Template;

use ChillDebug\Helper\Filesystem;


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

        $coverage = $this->codeCoverageMapper(json_decode(file_get_contents($fileName.'.cvg'), true));
        $server = file_get_contents($fileName.'.svr');
        $trace = file_get_contents($fileName.'.xt');

        $report = '';

        Filesystem::dump($fileName . '.html', sprintf($template, $fileName, '', $coverage, ''));
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
                    $report .= '<tr><td><a href="'. sprintf(self::PROTOCOL_URL, $file, $lineNumber) .'">'.$file.':'.$lineNumber.'</a></td>';
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
                    $report .= '<tr><td><a href="'. sprintf(self::PROTOCOL_URL, $file, $lineNumber) .'">'.$file.':'.$lineNumber.'</a></td>';
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
}