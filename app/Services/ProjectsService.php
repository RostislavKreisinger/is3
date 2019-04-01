<?php

namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Monkey\ImportSupport\Project;

/**
 * Class ProjectsService
 * @package App\Services
 */
class ProjectsService extends ApiService {
    /**
     * @return string
     */
    protected function getEndpoint(): string {
        return '/base/projects';
    }

    /**
     * @return Project
     */
    protected function getModel(): Model {
        return new Project;
    }
}