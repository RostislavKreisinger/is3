<?php
/**
 * Created by PhpStorm.
 * User: tomw
 * Date: 7/4/17
 * Time: 10:08 AM
 */

namespace Monkey\ImportSupport\Resource\Interfaces;


use Monkey\ImportSupport\Resource\Button\BaseButton;
use Monkey\ImportSupport\Resource\Button\ButtonList;
use Monkey\ImportSupport\Resource\ResourceStats;

interface IResource {

    /**
     * @param App\Model\Resource|int $resource
     * @param int $project_id
     * @return IResource
     */
    public static function factory($resource, $project_id);

    /**
     * @return IResource
     */
    public function getResourceDetail();

    /**
     * @param App\Model\Resource|int $resource
     * @return mixed
     */
    public function setResource($resource);

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return bool
     */
    public function isValidTester();

    /**
     * @return bool
     */
    public function isValidDaily();

    /**
     * @return bool
     */
    public function isValidHistory();

    /**
     * @return bool
     */
    public function isValidContinuity();

    /**
     * @return bool
     */
    public function getStateTester();

    /**
     * @return bool
     */
    public function getStateDaily();

    /**
     * @return bool
     */
    public function getStateHistory();

    /**
     * @return bool
     */
    public function getStateContinuity();

    /**
     * @return int
     */
    public function getProject_id();

    /**
     * @param int $project_id
     */
    public function setProject_id($project_id);

    /**
     * @return ResourceStats
     */
    public function getResourceStats();

    /**
     * @param ResourceStats $resourceStats
     * @return mixed
     */
    public function setResourceStats($resourceStats);

    /**
     *
     * @return ButtonList
     */
    public function getButtons();

    /**
     *
     * @return BaseButton
     */
    public function getButton($code);

    /**
     *
     * @param BaseButton $button
     */
    public function addButton(BaseButton $button);

    public function getConnectionDetail();
}