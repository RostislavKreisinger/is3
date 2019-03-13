<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 13.3.2019
 * Time: 12:47
 */

namespace App\Helpers\Monitoring\ImportFlow;


class GraphRowData
{

    /**
     * @var int
     */
    private $flowRuntimePercent = 0;

    /**
     * @var int
     */
    private $flowRuntime = 0;

    /**
     * @var int
     */
    private $importStepRuntimePercent = 0;

    /**
     * @var int
     */
    private $etlStepRuntimePercent = 0;

    /**
     * @var int
     */
    private $calcStepRuntimePercent = 0;

    /**
     * @var int
     */
    private $outputStepRuntimePercent = 0;

    /**
     * @var int
     */
    private $importTimeToRunPercent = 0;
    /**
     * @var int
     */
    private $etlTimeToRunPercent = 0;
    /**
     * @var int
     */
    private $calcTimeToRunPercent = 0;
    /**
     * @var int
     */
    private $outputTimeToRunPercent = 0;

    /**
     * @var string
     */
    private $unique;

    /**
     * @var bool
     */
    private $average = false;

    /**
     * @var bool
     */
    private $biggerThenAverage = false;

    /**
     * GraphRowData constructor.
     * @param string $unique
     * @param int $flowRuntime
     * @param int $maxFlowRuntime
     * @param bool $isAverageFlow
     */
    public function __construct(string $unique, int $flowRuntime, int $maxFlowRuntime, $isAverageFlow = false)
    {
        $this->setAverage($isAverageFlow);
        $this->setUnique($unique);
        $this->setFlowRuntime($flowRuntime);
        $this->setFlowRuntimePercent($this->getPercent($maxFlowRuntime, $flowRuntime, 0));
    }

    public function getPercent($base, $part, $round = 0){
        return round( $part / ($base / 100)  ,$round);
    }

    /**
     * @return int
     */
    public function getFlowRuntime(): int
    {
        return $this->flowRuntime;
    }

    /**
     * @param int $flowRuntime
     * @return GraphRowData
     */
    public function setFlowRuntime(int $flowRuntime): GraphRowData
    {
        $this->flowRuntime = $flowRuntime;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlowRuntimePercent(): int
    {
        return $this->flowRuntimePercent;
    }

    /**
     * @param int $flowRuntimePercent
     * @return GraphRowData
     */
    public function setFlowRuntimePercent(int $flowRuntimePercent): GraphRowData
    {
        $this->flowRuntimePercent = $flowRuntimePercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getImportStepRuntimePercent(): int
    {
        return $this->importStepRuntimePercent;
    }

    /**
     * @param int $importStepRuntimePercent
     * @return GraphRowData
     */
    public function setImportStepRuntimePercent(int $importStepRuntimePercent): GraphRowData
    {
        $this->importStepRuntimePercent = $importStepRuntimePercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtlStepRuntimePercent(): int
    {
        return $this->etlStepRuntimePercent;
    }

    /**
     * @param int $etlStepRuntimePercent
     * @return GraphRowData
     */
    public function setEtlStepRuntimePercent(int $etlStepRuntimePercent): GraphRowData
    {
        $this->etlStepRuntimePercent = $etlStepRuntimePercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getCalcStepRuntimePercent(): int
    {
        return $this->calcStepRuntimePercent;
    }

    /**
     * @param int $calcStepRuntimePercent
     * @return GraphRowData
     */
    public function setCalcStepRuntimePercent(int $calcStepRuntimePercent): GraphRowData
    {
        $this->calcStepRuntimePercent = $calcStepRuntimePercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getOutputStepRuntimePercent(): int
    {
        return $this->outputStepRuntimePercent;
    }

    /**
     * @param int $outputStepRuntimePercent
     * @return GraphRowData
     */
    public function setOutputStepRuntimePercent(int $outputStepRuntimePercent): GraphRowData
    {
        $this->outputStepRuntimePercent = $outputStepRuntimePercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getImportTimeToRunPercent(): int
    {
        return $this->importTimeToRunPercent;
    }

    /**
     * @param int $importTimeToRunPercent
     * @return GraphRowData
     */
    public function setImportTimeToRunPercent(int $importTimeToRunPercent): GraphRowData
    {
        $this->importTimeToRunPercent = $importTimeToRunPercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtlTimeToRunPercent(): int
    {
        return $this->etlTimeToRunPercent;
    }

    /**
     * @param int $etlTimeToRunPercent
     * @return GraphRowData
     */
    public function setEtlTimeToRunPercent(int $etlTimeToRunPercent): GraphRowData
    {
        $this->etlTimeToRunPercent = $etlTimeToRunPercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getCalcTimeToRunPercent(): int
    {
        return $this->calcTimeToRunPercent;
    }

    /**
     * @param int $calcTimeToRunPercent
     * @return GraphRowData
     */
    public function setCalcTimeToRunPercent(int $calcTimeToRunPercent): GraphRowData
    {
        $this->calcTimeToRunPercent = $calcTimeToRunPercent;
        return $this;
    }

    /**
     * @return int
     */
    public function getOutputTimeToRunPercent(): int
    {
        return $this->outputTimeToRunPercent;
    }

    /**
     * @param int $outputTimeToRunPercent
     * @return GraphRowData
     */
    public function setOutputTimeToRunPercent(int $outputTimeToRunPercent): GraphRowData
    {
        $this->outputTimeToRunPercent = $outputTimeToRunPercent;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnique(): string
    {
        return $this->unique;
    }

    /**
     * @param string $unique
     * @return GraphRowData
     */
    public function setUnique(string $unique): GraphRowData
    {
        $this->unique = $unique;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAverage(): bool
    {
        return $this->average;
    }

    /**
     * @param bool $average
     * @return GraphRowData
     */
    public function setAverage(bool $average): GraphRowData
    {
        $this->average = $average;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBiggerThenAverage(): bool
    {
        return $this->biggerThenAverage;
    }

    /**
     * @param bool $biggerThenAverage
     * @return GraphRowData
     */
    public function setBiggerThenAverage(bool $biggerThenAverage): GraphRowData
    {
        $this->biggerThenAverage = $biggerThenAverage;
        return $this;
    }



}