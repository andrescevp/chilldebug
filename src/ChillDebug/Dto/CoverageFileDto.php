<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 19:53
 */

namespace ChillDebug\Dto;


class CoverageFileDto
{

    private $file;

    private $coverageExecutedPercent;
    private $coverageNotExecutedPercent;
    private $coverageDeadPercent;

    /**
     * @var CoverageLineDto[]|array
     */
    private $coverageLines;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return CoverageFileDto
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getCoverageExecutedPercent()
    {
        return $this->coverageExecutedPercent;
    }

    /**
     * @param mixed $coveragePercent
     * @return CoverageFileDto
     */
    public function setCoverageExecutedPercent($coveragePercent)
    {
        $this->coverageExecutedPercent = $coveragePercent;
        return $this;
    }

    /**
     * @return array|CoverageLineDto[]
     */
    public function getCoverageLines()
    {
        return $this->coverageLines;
    }

    /**
     * @param array|CoverageLineDto[] $coverageLines
     * @return CoverageFileDto
     */
    public function setCoverageLines($coverageLines)
    {
        $this->coverageLines = $coverageLines;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoverageNotExecutedPercent()
    {
        return $this->coverageNotExecutedPercent;
    }

    /**
     * @param mixed $coverageNotExecutedPercent
     * @return CoverageFileDto
     */
    public function setCoverageNotExecutedPercent($coverageNotExecutedPercent)
    {
        $this->coverageNotExecutedPercent = $coverageNotExecutedPercent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoverageDeadPercent()
    {
        return $this->coverageDeadPercent;
    }

    /**
     * @param mixed $coverageDeadPercent
     * @return CoverageFileDto
     */
    public function setCoverageDeadPercent($coverageDeadPercent)
    {
        $this->coverageDeadPercent = $coverageDeadPercent;
        return $this;
    }


}