<?php

namespace Monkey\ImportSupport;


use App\Model\Project as ProjectModel;
use Exception;

/**
 * Description of Project
 *
 * @method Project find($id, $columns = array()) 
 * @author Tomas
 */
class Project extends ProjectModel {
    /**
     * @var bool $resourcesLoaded
     */
    private $resourcesLoaded = false;

    /**
     * @var Resource[] $resources
     */
    private $resources = [];

    /**
     * @return Resource[]
     * @throws Exception
     */
    public function getResources() {
        if (!$this->areResourcesLoaded()) {
            $data = parent::getResources()->where('allow_link', '!=', 0)->get();

            foreach ($data as $resource) {
                $this->resources[$resource->id] = Resource::factory($resource, $this->id);
            }

            $this->setResourcesLoaded(true);
        }

        return $this->resources;
    }


    /**
     * @param int $resourceId
     * @return Resource
     * @throws Exception
     */
    public function getResource($resourceId) {
        return $this->getResources()[$resourceId];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid() {
        foreach($this->getResources() as $resource){
            if(!$resource->isValid()){
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function areResourcesLoaded(): bool {
        return $this->resourcesLoaded;
    }

    /**
     * @param bool $resourcesLoaded
     * @return Project
     */
    public function setResourcesLoaded(bool $resourcesLoaded): Project {
        $this->resourcesLoaded = $resourcesLoaded;
        return $this;
    }
}