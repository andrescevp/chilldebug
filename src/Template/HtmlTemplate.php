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
    public function dump(array $informationAsArray, $kindOfInformation)
    {
        $functionMapper = $kindOfInformation . 'Mapper';

        if (!method_exists($this, $functionMapper)) {
            throw new \LogicException(
                'The kind of information: ' . $kindOfInformation . ' have not function candidate'
            );
        }

        $report = $this->$functionMapper($informationAsArray);


        Filesystem::dump($this->getFullReportPath('html'), $report);
    }

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    protected function codeCoverageMapper(array $informationAsArray)
    {
        $template = file_get_contents(__DIR__ . '/../Resources/templates/html_report.html');

        $header = '<header class="jumbotron">';
        $header .= '<h1>Debug report.</h1>';
        $header .= '<ul class="list-group">';
        $header .= '<li class="list-group-item">Total time: ' . $informationAsArray['total_time_execution'] . '</li>';
        $header .= '<li class="list-group-item">Peak memory usage: ' . $informationAsArray['peak_memory_usage'] . '</li>';
        $header .= '</ul>';
        $header .= '</header>';

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

        return sprintf($template, $report);
    }
}