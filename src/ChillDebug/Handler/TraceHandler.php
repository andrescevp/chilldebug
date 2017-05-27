<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 21:15
 */

namespace ChillDebug\Handler;


use ChillDebug\Dto\TraceDto;
use ChillDebug\Dto\TraceLineDto;

class TraceHandler
{
    public static function buildTrace(array $trace)
    {
        $traceDto = new TraceDto();
        $traceLines = array();
        foreach ($trace as $line) {
            if (preg_match('/^Version:.*(\d.\d.\d)/', $line)) {
                list($name, $version) = explode(':', $line);
                $traceDto->setVersion(trim($version));
            } elseif (preg_match('/^File format:.*\d/', $line)) {
                list($name, $format) = explode(':', $line);
                $traceDto->setFormat((trim($format)));
            } elseif (preg_match('/^TRACE START.*\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $traceStartInfo)) {

//                $dateFormat = 'Y-m-d';
//                $timeFormat = 'H:i:s';
//                list($date, $time) = explode(' ', $traceStartInfo[1]);
//                $time = strtotime(trim($time));
                $time = strtotime(trim($traceStartInfo[1]));
//                $date = strtotime(trim($date));
//                $dateTime = date($dateFormat, $dateTime);
//                var_dump($traceStartInfo[1], $date, $time, date($dateFormat, $date), date($timeFormat, $time));die;
                $traceDto->setStart($time);
//                $traceDto->setDate($date);
            } else if (preg_match('/^TRACE END.*\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $traceStartInfo)) {

//                $dateFormat = 'Y-m-d';
//                $timeFormat = 'H:i:s';
//                list($date, $time) = explode(' ', $traceStartInfo[1]);
                $time = strtotime(trim($traceStartInfo[1]));
//                $date = strtotime(trim($date));
//                $dateTime = date($dateFormat, $dateTime);
//                var_dump($traceStartInfo[1], $date, $time, date($dateFormat, $date), date($timeFormat, $time));die;
                $traceDto->setEnd($time);
//                $traceDto->setDate($date);
            } else {
                try {
                    $traceLines[] = TraceLineDto::build($line);
                } catch (\InvalidArgumentException $e) {
                    //nothing to do
                }
            }
        }

        $traceDto->setTraceLines($traceLines);

        return $traceDto;
    }
}