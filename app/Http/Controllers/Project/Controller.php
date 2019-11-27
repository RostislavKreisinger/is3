<?php

namespace App\Http\Controllers\Project;


use App\Exceptions\ProjectUserMissingException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Project\Resource\DetailController;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use Exception;
use App\Model\Project;
use Monkey\Constants\ImportFlow\Resource\ResourceSetting;
use Monkey\Menu\Menu;
use Monkey\Menu\MenuList;
use Monkey\View\View;
use Tr;

/**
 * Class Controller
 * @package App\Http\Controllers\Project
 */
class Controller extends BaseController {
    /**
     * @param Project $project
     * @return MenuList
     * @throws Exception
     * @throws ProjectUserMissingException
     */
    protected function prepareMenu($project = null) {
        View::share('project', $project);
        $menu = $this->getMenu();

        if ($project !== null) {
            $resources = new Menu($project->name, '#');
            $resources->setOpened(true);
            $resourceSettings = $project->resourceSettings;

            foreach ($resourceSettings as $resourceSetting) {
                $menuItem = new Menu(
                    Tr::_($resourceSetting->resource->btf_name),
                    action(DetailController::routeMethod('getIndex'), ['project_id' => $project->id, 'resource_id' => $resourceSetting->resource_id])
                );

                if (!$resourceSetting->isValid()) {
                    $menuItem->addClass('invalid');
                }
                $resources->addMenuItem($menuItem);
            }
            if ($resourceSettings->isEmpty()) {
                $resources->addMenuItem(new Menu("-- EMPTY --", ""));
            }
            $menu->addMenuItem($resources);
            $projectUser = $project->user;

            if (empty($projectUser)) {
                throw new ProjectUserMissingException($project->id, $project->user_id);
            }

            $userProjects = new Menu('Projects', '#');

            foreach ($projectUser->projects->where('id', '!=', $project->id) as $userProject) {
                $menuItem = new Menu(
                    $userProject->name,
                    action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $userProject->id])
                );

                $menuItem->setTitle($userProject->id);

                if ($userProject->resourceSettings->where('active', ResourceSetting::ERROR)->count() > 0) {
                    $menuItem->addClass('invalid');
                }

                $userProjects->addMenuItem($menuItem);
            }

            if (count($userProjects->getList()) === 0) {
                $userProjects->addMenuItem(new Menu("-- EMPTY --", null));
            }

            $menu->addMenuItem($userProjects);
        }

        return $menu;
    }
}
