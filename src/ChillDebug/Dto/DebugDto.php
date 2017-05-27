<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 21:28
 */

namespace ChillDebug\Dto;


class DebugDto
{
    private $coverage;

    private $request;

    private $trace;

    private $maxMemoryUsed;

    /**
     * @return mixed
     */
    public function getMaxMemoryUsed()
    {
        return $this->maxMemoryUsed;
    }

    /**
     * @param mixed $maxMemoryUsed
     * @return DebugDto
     */
    public function setMaxMemoryUsed($maxMemoryUsed)
    {
        $this->maxMemoryUsed = $maxMemoryUsed;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * @param mixed $coverage
     * @return DebugDto
     */
    public function setCoverage($coverage)
    {
        $this->coverage = $coverage;
        return $this;
    }

    /**
     * @return RequestDto
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     * @return DebugDto
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * @param mixed $trace
     * @return DebugDto
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
        return $this;
    }



}