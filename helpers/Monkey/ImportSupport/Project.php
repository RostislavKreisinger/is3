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
     * @var Resource[] $resources
     */
    private $resources;

    /**
     * @return Resource[]
     * @throws Exception
     */
    public function getResources() {
        if ($this->resources === null) {
            $data = parent::getResources()->where('allow_link', '!=', 0)->get();
            $this->resources = [];

            foreach ($data as $resource) {
                $this->resources[$resource->id] = Resource::factory($resource, $this->id);
            }
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
}