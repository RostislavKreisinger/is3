<?php

namespace App\Http\Controllers\Debug;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Monkey\ImportEshopDataObjects\Base\Differences;
use Monkey\ImportEshopDataObjects\Entity\Simple\Alias;
use Monkey\ImportEshopSdk\Helper\DifferencesHelper;
use Monkey\ImportSupport\Project;

/**
 * Class DifferencesController
 * @package App\Http\Controllers\Debug
 * @author Lukáš Kielar
 */
class DifferencesController extends Controller {
    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     */
    public function add(Project $project_id, int $resource_id, Request $request) {
        switch ($request->input('type')) {
            case 'column':
                $alias = new Alias($request->input('column') ?? '', $request->input('alias') ?? '');

                if (!empty($request->input('raw'))) {
                    $alias->setRaw($request->input('raw'));
                }

                $helper = new DifferencesHelper(
                    $project_id->getResourceSettings($resource_id)->get()[0]->id,
                    $request->input('endpoint')
                );
                $helper->addColumnAlias($alias);
                break;
        }
    }

    /**
     * @param Project $project_id
     * @param int $resource_id
     * @param Request $request
     * @return Differences
     */
    public function load(Project $project_id, int $resource_id, Request $request) {
        $helper = new DifferencesHelper(
            $project_id->getResourceSettings($resource_id)->get()[0]->id,
            $request->input('endpoint')
        );
        return objectToArray($helper->getDifferences());
    }
}