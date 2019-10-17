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
     * @var int
     */
    private $projectId;

    /**
     * @var bool
     */
    private $average = false;

    /**
     * @var bool
     */
    private $biggerThanAverage = false;

    /**
     * @var int
     */
    private $graphSize;

    /**
     * @var int
     */
    private $actualActivePart = 0;

    /**
     * GraphRowData constructor.
     * @param int $projectId
     * @param string $unique
     * @param int $flowRuntime
     * @param int $maxFlowRuntime
     * @param bool $isAverageFlow
     */
    public function __construct(int $projectId, string $unique, int $flowRuntime, int $maxFlowRuntime, $isAverageFlow = false)
    {
        $this->setProjectId($projectId);
        $this->setAverage($isAverageFlow);
        $this->setUnique($unique);
        $this->setFlowRuntime($flowRuntime);
        $this->setFlowRuntimePercent($this->getPercent($maxFlowRuntime, $flowRuntime, 0));
        $this->setGraphSize($this->calculateGraphSize($maxFlowRuntime, $flowRuntime));
    }

    private function calculateGraphSize($base, $part){
        if($base == 0){
            return 0;
        }
        return round( $part / ($base / 500) ) + 30;
    }

    public function getPercent($base, $part, $round = 0){
        if($base == 0){
            return 0;
        }
        return round( $part / ($base / 100)  ,$round);
    }

    private function getCirclePart($part){
        //vde($this->getHoleUnrealRuntime());
        if($this->getWholeUnrealRuntime() == 0){
            return 0;
        }
        return (int) round( $part / ($this->getWholeUnrealRuntime() / 360));
    }

    /**
     * @return int
     */
    public function getImportTimeToStartOffset(){
        return 0;
    }

    /**
     * @param $value
     * @return int
     */
    public function isBiggerThan180($value){
        return ($value > 180?1:0);
    }

    /**
     * @return int
     */
    public function getGraphSize(): int{
        return $this->graphSize;
    }

    /**
     * @param int $graphSize
     * @return GraphRowData
     */
    private function setGraphSize(int $graphSize): GraphRowData{
        $this->graphSize = $graphSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getImportTimeToStartValue(){
        return $this->getCirclePart($this->getImportTimeToRun());
    }

    /**
     * @return int
     */
    public function getImportRuntimeOffset(){
        return $this->getImportTimeToStartOffset() + $this->getImportTimeToStartValue();
    }

    /**
     * @return int
     */
    public function getImportRuntimeValue(){
        return $this->getCirclePart($this->getImportStepRuntime());
    }

    /**
     * @return int
     */
    public function getEtlTimeToStartOffset(){
        return $this->getImportRuntimeOffset() + $this->getImportRuntimeValue();
    }

    /**
     * @return int
     */
    public function getEtlTimeToStartValue(){
        return $this->getCirclePart($this->getEtlTimeToRun());

    }

    /**
     * @return int
     */
    public function getEtlRuntimeOffset(){
        return $this->getEtlTimeToStartOffset() + $this->getEtlTimeToStartValue();
    }

    /**
     * @return int
     */
    public function getEtlRuntimeValue(){
        return $this->getCirclePart($this->getEtlStepRuntime());
    }

    /**
     * @return int
     */
    public function getCalcTimeToStartOffset(){
        return $this->getEtlRuntimeOffset() + $this->getEtlRuntimeValue();
    }

    /**
     * @return int
     */
    public function getCalcTimeToStartValue(){
        return $this->getCirclePart($this->getCalcTimeToRun());
    }

    /**
     * @return int
     */
    public function getCalcRuntimeOffset(){
        return $this->getCalcTimeToStartOffset() + $this->getCalcTimeToStartValue();
    }

    /**
     * @return int
     */
    public function getCalcRuntimeValue(){
        return $this->getCirclePart($this->getCalcStepRuntime());

    }

    /**
     * @return int
     */
    public function getOutputTimeToStartOffset(){
        return $this->getCalcRuntimeOffset() + $this->getCalcRuntimeValue();
    }

    /**
     * @return int
     */
    public function getOutputTimeToStartValue(){
        return $this->getCirclePart($this->getOutputTimeToRun());
    }

    /**
     * @return int
     */
    public function getOutputRuntimeOffset(){
        return $this->getOutputTimeToStartOffset() + $this->getOutputTimeToStartValue();
    }

    /**
     * @return int
     */
    public function getOutputRuntimeValue(){
        return $this->getCirclePart($this->getOutputStepRuntime());
    }

    /**
     * @return int
     */
    private function getWholeUnrealRuntime(){
        return $this->getImportTimeToRun() + $this->getImportStepRuntime()+
            $this->getEtlTimeToRun() + $this->getEtlStepRuntime()+
            $this->getCalcTimeToRun() + $this->getCalcStepRuntime()+
            $this->getOutputTimeToRun() + $this->getOutputStepRuntime();
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
    public function isBiggerThanAverage(): bool
    {
        return $this->biggerThanAverage;
    }

    /**
     * @param bool $biggerThanAverage
     * @return GraphRowData
     */
    public function setBiggerThanAverage(bool $biggerThanAverage): GraphRowData
    {
        $this->biggerThanAverage = $biggerThanAverage;
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
     * @param int $time
     * @return string
     */
    public function formatSeconds(int $time):string{
        $hours = floor($time / 3600);
        $restTime = $time % 3600;
        $minutes = floor($restTime / 60);
        $seconds = $restTime % 60;
        return ($hours !== 0 ? $hours.' h ' : '').($minutes !== 0 ? $minutes.' m ' : '').$seconds.' s';
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

    /**
     * @return int
     */
    public function getProjectId(): int{
        return $this->projectId;
    }

    /**
     * @param int $projectId
     * @return GraphRowData
     */
    public function setProjectId(int $projectId): GraphRowData{
        $this->projectId = $projectId;
        return $this;
    }



    /**
     * @return int
     */
    public function getActualActivePart(): int{

        $this->setActualActivePart(0, $this->getImportTimeToRun());
        $this->setActualActivePart(1, $this->getImportStepRuntime());
        $this->setActualActivePart(2, $this->getEtlTimeToRun());
        $this->setActualActivePart(3, $this->getEtlStepRuntime());
        $this->setActualActivePart(4, $this->getCalcTimeToRun());
        $this->setActualActivePart(5, $this->getCalcStepRuntime());
        $this->setActualActivePart(6, $this->getOutputTimeToRun());
        $this->setActualActivePart(7, $this->getOutputStepRuntime());

        return $this->actualActivePart;
    }

    /**
     * @param int $actualActivePart
     * @return GraphRowData
     */
    private function setActualActivePart(int $actualActivePart, int $time): GraphRowData{
        if($actualActivePart > $this->actualActivePart AND $time > 0){
            $this->actualActivePart = $actualActivePart;
        }
        return $this;
    }





}