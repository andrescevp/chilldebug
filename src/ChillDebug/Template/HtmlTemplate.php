<?php

namespace ChillDebug\Template;

use ChillDebug\Dto\CoverageDto;
use ChillDebug\Dto\CoverageFileDto;
use ChillDebug\Dto\DebugDto;
use ChillDebug\Dto\RequestDto;
use ChillDebug\Dto\TraceDto;
use ChillDebug\Dto\TraceLineDto;
use ChillDebug\Handler\CoverageHandler;
use ChillDebug\Handler\RequestHandler;
use ChillDebug\Helper\Filesystem;
use ChillDebug\Helper\UserInterface;


/**
 * Class HtmlTemplate
 *
 * @package ChillDebug\Template
 */
class HtmlTemplate extends Abstracted
{
    const PROTOCOL_URL = 'ide://open?url=file://%s&line=%d';

    /**
     * @param array $stackTrace
     *
     */
    public function dump($fileName, DebugDto $debugDto)
    {
        parent::dump($fileName, $debugDto);

        $template = file_get_contents(__DIR__ . '/../Resources/templates/html_report.html');
        $coverage = $this->codeCoverageMapper($debugDto->getCoverage());
        $server = $this->serverMapper($debugDto->getRequest());
        $trace = $this->stackTraceMapper($debugDto->getTrace());

        list($type, $request) = $this->getGeneralInformation($debugDto);

        $type = '<span class="badge badge-info">'.$type.'</span>';
        $arguments = '<span class="badge badge-info"><a href="'.$request.'">'.$request.'</a></span>';

        $memory = '<span class="badge badge-warning">Max. Mem used: '.$debugDto->getMaxMemoryUsed().'</span>';

        if ($this->config->generateHtmlView) {
            Filesystem::dump($fileName . '.html', sprintf(
                    $template,
                    $fileName,
                    $type,
                    $arguments,
                    $memory,
                    $trace,
                    $coverage,
                    $server
                )
            );
        }
    }

    /**
     * @param array $informationAsArray
     *
     * @return mixed
     */
    protected function codeCoverageMapper(CoverageDto $coverageDto)
    {

        $report = '<table class="table">';

        $coverageLines = $coverageDto->getCoverageFiles();

        /** @var CoverageFileDto $fileInformation */
        foreach ($coverageLines as $fileInformation) {
            $file = $fileInformation->getFile();
            $report .= '<tr><td><strong>File:</strong></td><td colspan="2">' . $file . '</td></tr>';
            $report .= '<tr class="alert-info"><td><strong>Lines executed percent:</strong></td><td colspan="2">' . $fileInformation->getCoverageExecutedPercent() . ' % </td></tr>';
            $report .= '<tr class="alert-warning"><td><strong>Lines not executed percent:</strong></td><td colspan="2">' . $fileInformation->getCoverageNotExecutedPercent() . ' % </td></tr>';
            $report .= '<tr class="alert-danger"><td><strong>Lines dead percent:</strong></td><td colspan="2">' . $fileInformation->getCoverageDeadPercent() . ' % </td></tr>';
            $report .= '<tr><td><strong>File </strong></td><td><strong>Code</strong></td></tr>';

            $coverageLines = $fileInformation->getCoverageLines();
            foreach ($coverageLines as $lineInformation) {
                $lineNumber = $lineInformation->getFileLine();
                $report .= '<tr><td><a href="' . sprintf(self::PROTOCOL_URL, $file,
                        $lineNumber) . '">' . $file . ':' . $lineNumber . '</a></td>';
                $report .= '<td><pre class="prettyprint">' . $lineInformation->getLineContent() . '</pre></td>';
                $report .= '</tr>';
            }
        }
        $report .= '</table>';

        return $report;
    }

    protected function serverMapper(RequestDto $requestDto)
    {
        $header = '<div class="alert alert-info">';
        $header .= '<ul class="list-group">';
        $header .= '<li class="list-group-item">Time: ' . $requestDto->getTime() . '</li>';
        $header .= '</ul>';
        $header .= '</div>';

        $report = $header;


        $report .= '<ul class="table">';

        $report .= '<li class="list-group-item"><strong>SERVER</strong> <pre class="prettyprint">' . json_encode($requestDto->getServer(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>REQUEST</strong> <pre class="prettyprint">' . json_encode($requestDto->getRequest(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>GET</strong> <pre class="prettyprint">' . json_encode($requestDto->getGet(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>POST</strong> <pre class="prettyprint">' . json_encode($requestDto->getPost(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>FILES</strong> <pre class="prettyprint">' . json_encode($requestDto->getFiles(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>COOKIES</strong> <pre class="prettyprint">' . json_encode($requestDto->getCookies(),
                JSON_PRETTY_PRINT) . '</pre></li>';
        $report .= '<li class="list-group-item"><strong>SESSION</strong> <pre class="prettyprint">' . json_encode($requestDto->getSession(),
                JSON_PRETTY_PRINT) . '</pre></li>';

        $report .= '</ul>';

        return $report;
    }

    protected function stackTraceMapper(TraceDto $traceDto)
    {
        $header = '<div class="alert alert-info">';
        $header .= '<ul class="list-group">';
        $header .= '<li class="list-group-item">Start: ' . $traceDto->getStart() . '</li>';
        $header .= '<li class="list-group-item">End: ' . $traceDto->getEnd() . '</li>';
        $header .= '</ul>';
        $header .= '</div>';

        $report = $header;

        $report .= '<table class="table table-bordered">';
        $report .= '<thead>';
        $report .= '<tr>';
        $report .= '<td>Level</td>';
        $report .= '<td>Function Number</td>';
        $report .= '<td>Type</td>';
        $report .= '<td>Time index</td>';
        $report .= '<td>Memory Usage</td>';
        $report .= '<td>Defined by</td>';
        $report .= '<td>Funcion</td>';
        $report .= '<td>Nº parameters</td>';
        $report .= '<td>Included/required file</td>';
        $report .= '</tr>';
        $report .= '</thead>';
        $traceLines = $traceDto->getTraceLines();
        foreach ($traceLines as $traceLine) {
            $typeLine = $traceLine->getType();
            if($typeLine == TraceLineDto::TYPE_ENTRY) {
                $report .= '<tr>';
                $report .= '<td>' . $traceLine->getNestingLevel() . "</td>";
                $report .= '<td>' . $traceLine->getFunctionNumber() . "</td>";
                $report .= '<td>' . $typeLine . "</td>";
                $report .= '<td>' . $traceLine->getTimeIndex() . "</td>";
                $report .= '<td>' . $traceLine->getMemoryUsage() . "</td>";
                $report .= '<td>' . $traceLine->getDefinedBy() . "</td>";

                $report .= '<td><a href="'.sprintf(self::PROTOCOL_URL, $traceLine->getFileName(),
                        $traceLine->getFileLine()).'">'.
                    $traceLine->getFileName() . ':' .
                    $traceLine->getFileLine() .
                    '</a>';

                $report .= '<pre class="prettyprint">' . $traceLine->getFunctionName() . '(' .
                    ((null == $traceLine->getParameters()) ? '' : $traceLine->getParameters()) .
                    ")</pre></td>";
                $report .= '<td>' . $traceLine->getNumbersParameters() . "</td>";
                $report .= '<td>' . $traceLine->getRequiredIncludedFile() . "</td>";
                continue;

            }

            if($typeLine == TraceLineDto::TYPE_EXIT) {
                $report .= '<tr>';
                $report .= '<td>' . $traceLine->getNestingLevel() . "</td>";
                $report .= '<td>' . $traceLine->getFunctionNumber() . "</td>";
                $report .= '<td>' . $typeLine . "</td>";
                $report .= '<td>' . $traceLine->getTimeIndex() . "</td>";
                $report .= '<td>' . $traceLine->getMemoryUsage() . "</td>";
                continue;
            }

            if($typeLine == TraceLineDto::TYPE_R) {
                $report .= '<tr>';
                $report .= '<td>' . $traceLine->getNestingLevel() . "</td>";
                $report .= '<td>' . $traceLine->getFunctionNumber() . "</td>";
                $report .= '<td>' . $typeLine . "</td>";
                $report .= '<td colspan="3"></td>';
                $report .= '<td>' . $traceLine->getFunctionName() . "</td>";
                $report .= '<td colspan="2"></td>';
                continue;
            }

            $report .= '</tr>';
        }
        $report .= '</table>';

        $report .= '';

        return $report;
    }


}