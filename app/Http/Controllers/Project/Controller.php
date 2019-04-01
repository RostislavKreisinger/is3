<?php

namespace App\Http\Controllers\Project;


use App\Exceptions\ProjectUserMissingException;
use App\Http\Controllers\Controller as BaseController;
use Exception;
use Monkey\ImportSupport\Project;
use Monkey\Menu\Menu;
use Monkey\Menu\MenuList;
use Monkey\View\View;

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
            $resources = new Menu($project->name, url("#"));
            $resources->setOpened(true);
            $projectResources = $project->getResources();

            foreach ($project->resourceSettings as $resourceSetting) {
                $menuItem = new Menu($resourceSetting->resourceName->name, url("/resource-settings/{$resourceSetting->id}"));

                if ($resourceSetting->active === 3) {
                    $menuItem->addClass('alert-danger');
                }

                $resources->addMenuItem($menuItem);
            }
            /*
            foreach ($projectResources as $resource) {
                $menuItem = new Menu(
                    Tr::_($resource->btf_name),
//                    action(DetailController::routeMethod('getIndex'), ['project_id' => $project->id, 'resource_id' => $resource->id])
                    url("/resource-settings/{}")
                );

                if (!$resource->isValid()) {
                    $menuItem->addClass('invalid');
                }
                $resources->addMenuItem($menuItem);
            }*/

            if (count($projectResources) == 0) {
                $resources->addMenuItem(new Menu("-- EMPTY --", ""));
            }
            $menu->addMenuItem($resources);
            $projectUser = $project->getUser();

            if (empty($projectUser)) {
                throw new ProjectUserMissingException($project->id, $project->user_id);
            }

            $userProjects = new Menu('Projects', '#');
            foreach ($projectUser->getProjects() as $userProject) {
                if ($project->id == $userProject->id) continue;
                $menuItem = new Menu(
                    $userProject->name,
                    url("/project/{$userProject->id}")
                );
                $menuItem->setTitle($userProject->id);
                if (!$userProject->isValid()) {
                    $menuItem->addClass('invalid');
                }
                $userProjects->addMenuItem($menuItem);
            }
            if (count($userProjects->getList()) == 0) {
                $userProjects->addMenuItem(new Menu("-- EMPTY --", null));
            }
            $menu->addMenuItem($userProjects);
        }

        return $menu;
    }
}