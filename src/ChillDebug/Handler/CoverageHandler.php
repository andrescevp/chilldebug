<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 20:04
 */

namespace ChillDebug\Handler;


use ChillDebug\Dto\CoverageDto;
use ChillDebug\Dto\CoverageFileDto;
use ChillDebug\Dto\CoverageLineDto;
use ChillDebug\Helper\Filesystem;

class CoverageHandler
{
    private static $coverages = [];

    public static function buildCoverageDto(array $coverage)
    {
        $coverageDto = new CoverageDto();
        $coverageFiles = array();
        foreach ($coverage as $file => $lines) {
            if (!Filesystem::isFile($file)) {
                continue;
            }

            $coverageFileDto = new CoverageFileDto();
            $coverageLines = array();
            $coverageFileDto->setFile($file);

            $fileAsArray               = file($file);
            $totalAmountFileLines      = count($fileAsArray);

            $linesExecuted              = 0;
            $linesUnused              = 0;
            $linesDead              = 0;


            foreach ($lines as $line => $type) {
                $coverageLines[$line] = (new CoverageLineDto())->setFile($file)
                    ->setLineContent(trim($fileAsArray[$line - 1]))
                    ->setType($type)
                    ->setFileLine($line);

                if ($coverageLines[$line]->getType() == CoverageLineDto::TYPE_EXECUTED) {
                    $linesExecuted++;
                }

                if ($coverageLines[$line]->getType() == CoverageLineDto::TYPE_NOT_EXECUTED) {
                    $linesUnused++;
                }

                if ($coverageLines[$line]->getType() == CoverageLineDto::TYPE_DEAD) {
                    $linesDead++;
                }

            }

            $coverageFileDto->setCoverageLines($coverageLines);
//
//            $this->codeCoverage[$file]['lines_coverage'] = (($linesCovered / $totalAmountFileLines) * 100);
            $coverageFileDto->setCoverageExecutedPercent(($linesExecuted / $totalAmountFileLines) * 100);
            $coverageFileDto->setCoverageNotExecutedPercent(($linesUnused / $totalAmountFileLines) * 100);
            $coverageFileDto->setCoverageDeadPercent(($linesDead / $totalAmountFileLines) * 100);

            $coverageFiles[] = $coverageFileDto;
        }

        $coverageDto->setCoverageFiles($coverageFiles);

        self::$coverages[md5(serialize($coverageDto))] = $coverage;

        return $coverageDto;
    }

    public static function getCoverageRaw($key)
    {
        return self::$coverages[$key];
    }
}