<?php

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ProjectType
 * @package App\Model
 */
class ProjectType extends Model {
    use SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $table = 'project_type';

    /**
     * @inheritdoc
     */
    public function getConnection() {
        return MDDatabaseConnections::getMasterAppConnection();
    }
}