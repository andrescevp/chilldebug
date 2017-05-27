<?php

namespace ChillDebug\Dto;

/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 18:34
 */
class CoverageLineDto
{
    const TYPE_EXECUTED = 'Executed';
    const TYPE_NOT_EXECUTED = 'NotExecuted';
    const TYPE_DEAD = 'DeadCode';
    private $file;

    private $fileLine;

    private $lineContent;

    private $types = array(
        '1' => self::TYPE_EXECUTED,
        '-1' => self::TYPE_NOT_EXECUTED,
        '-2' => self::TYPE_DEAD
    );

    private $type;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return CoverageLineDto
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileLine()
    {
        return $this->fileLine;
    }

    /**
     * @param mixed $fileLine
     * @return CoverageLineDto
     */
    public function setFileLine($fileLine)
    {
        $this->fileLine = $fileLine;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineContent()
    {
        return $this->lineContent;
    }

    /**
     * @param mixed $lineContent
     * @return CoverageLineDto
     */
    public function setLineContent($lineContent)
    {
        $this->lineContent = $lineContent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return CoverageLineDto
     */
    public function setType($type)
    {
        if (!array_key_exists($type, $this->types)) {
            throw new \RuntimeException($type . ' is not a valid type');
        }

        $this->type = $this->types[$type];
        return $this;
    }


}