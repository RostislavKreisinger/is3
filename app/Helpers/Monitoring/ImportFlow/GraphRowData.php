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
     * @var int
     */
    private $importStepRuntime = 0;

    /**
     * @var int
     */
    private $etlStepRuntime = 0;

    /**
     * @var int
     */
    private $calcStepRuntime = 0;

    /**
     * @var int
     */
    private $outputStepRuntime = 0;

    /**
     * @var int
     */
    private $importTimeToRun = 0;
    /**
     * @var int
     */
    private $etlTimeToRun = 0;
    /**
     * @var int
     */
    private $calcTimeToRun = 0;
    /**
     * @var int
     */
    private $outputTimeToRun = 0;
    

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

    /**
     * @return int
     */
    public function getImportStepRuntime(): int{
        return $this->importStepRuntime;
    }

    /**
     * @param int $importStepRuntime
     * @return GraphRowData
     */
    public function setImportStepRuntime(int $importStepRuntime): GraphRowData{
        $this->importStepRuntime = $importStepRuntime;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtlStepRuntime(): int{
        return $this->etlStepRuntime;
    }

    /**
     * @param int $etlStepRuntime
     * @return GraphRowData
     */
    public function setEtlStepRuntime(int $etlStepRuntime): GraphRowData{
        $this->etlStepRuntime = $etlStepRuntime;
        return $this;
    }

    /**
     * @return int
     */
    public function getCalcStepRuntime(): int{
        return $this->calcStepRuntime;
    }

    /**
     * @param int $calcStepRuntime
     * @return GraphRowData
     */
    public function setCalcStepRuntime(int $calcStepRuntime): GraphRowData{
        $this->calcStepRuntime = $calcStepRuntime;
        return $this;
    }

    /**
     * @return int
     */
    public function getOutputStepRuntime(): int{
        return $this->outputStepRuntime;
    }

    /**
     * @param int $outputStepRuntime
     * @return GraphRowData
     */
    public function setOutputStepRuntime(int $outputStepRuntime): GraphRowData{
        $this->outputStepRuntime = $outputStepRuntime;
        return $this;
    }

    /**
     * @return int
     */
    public function getImportTimeToRun(): int{
        return $this->importTimeToRun;
    }

    /**
     * @param int $importTimeToRun
     * @return GraphRowData
     */
    public function setImportTimeToRun(int $importTimeToRun): GraphRowData{
        $this->importTimeToRun = $importTimeToRun;
        return $this;
    }

    /**
     * @return int
     */
    public function getEtlTimeToRun(): int{
        return $this->etlTimeToRun;
    }

    /**
     * @param int $etlTimeToRun
     * @return GraphRowData
     */
    public function setEtlTimeToRun(int $etlTimeToRun): GraphRowData{
        $this->etlTimeToRun = $etlTimeToRun;
        return $this;
    }

    /**
     * @return int
     */
    public function getCalcTimeToRun(): int{
        return $this->calcTimeToRun;
    }

    /**
     * @param int $calcTimeToRun
     * @return GraphRowData
     */
    public function setCalcTimeToRun(int $calcTimeToRun): GraphRowData{
        $this->calcTimeToRun = $calcTimeToRun;
        return $this;
    }

    /**
     * @return int
     */
    public function getOutputTimeToRun(): int{
        return $this->outputTimeToRun;
    }

    /**
     * @param int $outputTimeToRun
     * @return GraphRowData
     */
    public function setOutputTimeToRun(int $outputTimeToRun): GraphRowData{
        $this->outputTimeToRun = $outputTimeToRun;
        return $this;
    }





}