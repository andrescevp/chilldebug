<?php

namespace ChillDebug\Dto;
use ChillDebug\Helper\Filesystem;

/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 18:34
 */
class TraceLineDto
{
    const TYPE_ENTRY = 'entry';
    const TYPE_EXIT = 'exit';
    const TYPE_R = 'return';


    private $types = array(
        '0' => self::TYPE_ENTRY,
        '1' => self::TYPE_EXIT,
        'R' => self::TYPE_R,
    );

    private $definedBys = array(
        '1' => 'user_defined',
        '0' => 'internal_function'
    );

    protected $nestingLevel;

    protected $functionCalls;

    protected $type;

    protected $timeIndex = null;

    protected $memoryUsage = null;

    protected $functionName = null;

    protected $definedBy = null;

    protected $requiredIncludedFile = null;

    protected $fileName = null;

    protected $fileLine = null;

    protected $numbersParameters = null;

    protected $parameters = null;

    private function __construct()
    {

    }

    public static function build($line)
    {
        @list($lvl, $fn, $t, $ti, $mu, $fnm, $tfun, $nirf, $fin, $ln, $nop, $p) = preg_split('/\t/', $line, 12);

        if (!Filesystem::isFile($fin)) {
            throw new \InvalidArgumentException($fin . ' is no an allowed file');
        }

        $self = new self();

        $self->setNestingLevel($lvl)
                ->setFunctionCalls($fn)
            ->setType($t)
            ->setTimeIndex($ti)
            ->setMemoryUsage($mu)
            ->setFunctionName($fnm)
            ->setDefinedBy($tfun)
            ->setRequiredIncludedFile($nirf)
            ->setFileName($fin)
            ->setFileLine($ln)
            ->setNumbersParameters($nop)
            ->setParameters($p);

        return $self;
    }
    /**
     * @return mixed
     */
    public function getNestingLevel()
    {
        return $this->nestingLevel;
    }

    /**
     * @param mixed $nestingLevel
     * @return TraceLineDto
     */
    public function setNestingLevel($nestingLevel)
    {
        $this->nestingLevel = $nestingLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunctionNumber()
    {
        return $this->functionCalls;
    }

    /**
     * @param mixed $functionCalls
     * @return TraceLineDto
     */
    public function setFunctionCalls($functionCalls)
    {
        $this->functionCalls = $functionCalls;
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
     * @return TraceLineDto
     */
    public function setType($type)
    {
        if (null == $type) {
            return $this;
        }

        if (!array_key_exists($type, $this->types)) {
            throw new \InvalidArgumentException($type . ' is not a valid type');
        }

        $this->type = $this->types[$type];
        return $this;
    }

    /**
     * @return null
     */
    public function getTimeIndex()
    {
        return $this->timeIndex;
    }

    /**
     * @param null $timeIndex
     * @return TraceLineDto
     */
    public function setTimeIndex($timeIndex)
    {
        $this->timeIndex = $timeIndex;
        return $this;
    }

    /**
     * @return null
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * @param null $memoryUsage
     * @return TraceLineDto
     */
    public function setMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = $memoryUsage;
        return $this;
    }

    /**
     * @return null
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    /**
     * @param null $functionName
     * @return TraceLineDto
     */
    public function setFunctionName($functionName)
    {
        $this->functionName = $functionName;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefinedBy()
    {
        return $this->definedBy;
    }

    /**
     * @param null $definedBy
     * @return TraceLineDto
     */
    public function setDefinedBy($definedBy)
    {
        if (null == $definedBy) {
            return $this;
        }

        if (!array_key_exists($definedBy, $this->definedBys)) {
            throw new \InvalidArgumentException($definedBy . ' is not a valid value');
        }

        $this->definedBy = $this->definedBy[$definedBy];
        return $this;
    }

    /**
     * @return null
     */
    public function getRequiredIncludedFile()
    {
        return $this->requiredIncludedFile;
    }

    /**
     * @param null $requiredIncludedFile
     * @return TraceLineDto
     */
    public function setRequiredIncludedFile($requiredIncludedFile)
    {
        $this->requiredIncludedFile = $requiredIncludedFile;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param null $fileName
     * @return TraceLineDto
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileLine()
    {
        return $this->fileLine;
    }

    /**
     * @param null $fileLine
     * @return TraceLineDto
     */
    public function setFileLine($fileLine)
    {
        $this->fileLine = $fileLine;
        return $this;
    }

    /**
     * @return null
     */
    public function getNumbersParameters()
    {
        return $this->numbersParameters;
    }

    /**
     * @param null $numbersParameters
     * @return TraceLineDto
     */
    public function setNumbersParameters($numbersParameters)
    {
        $this->numbersParameters = $numbersParameters;
        return $this;
    }

    /**
     * @return null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param null $parameters
     * @return TraceLineDto
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }


}