<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 21:43
 */

namespace ChillDebug\Handler;


use ChillDebug\Dto\DebugDto;

class DebugHandler
{
    public static function buildDebug($coverage, $trace, $time = null)
    {

        $coverage = CoverageHandler::buildCoverageDto($coverage);
        $trace = TraceHandler::buildTrace($trace);
        $request = RequestHandler::buildRequest($time);

        $debugDto = (new DebugDto())
            ->setCoverage($coverage)
            ->setTrace($trace)
            ->setRequest($request);

        $debugDto->setMaxMemoryUsed(xdebug_peak_memory_usage());

        return $debugDto;
    }
}