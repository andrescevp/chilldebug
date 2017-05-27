<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 20:56
 */

namespace ChillDebug\Dto;


class CoverageDto
{
    private $coverageFiles;

    /**
     * @return mixed
     */
    public function getCoverageFiles()
    {
        return $this->coverageFiles;
    }

    /**
     * @param mixed $coverageFiles
     * @return CoverageDto
     */
    public function setCoverageFiles($coverageFiles)
    {
        $this->coverageFiles = $coverageFiles;
        return $this;
    }


}