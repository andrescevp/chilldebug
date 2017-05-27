<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 18:54
 */

namespace ChillDebug\Dto;


class TraceDto
{
    private $version;

    private $format;

    private $start;

    private $end;

    private $date;

    /**
     * @var TraceLineDto[]|array
     */
    private $traceLines = array();

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return TraceLineDto[]
     */
    public function getTraceLines()
    {
        return $this->traceLines;
    }

    /**
     * @param TraceLineDto[] $traceLines
     */
    public function setTraceLines($traceLines)
    {
        $this->traceLines = $traceLines;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return TraceDto
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }


}