<?php

namespace App\Migration;


use Illuminate\Database\Connection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;

/**
 * Class AMigration
 * @package App\Migration
 */
abstract class AMigration extends Migration {
    /**
     * @var Builder $schema
     */
    private $schema;

    /**
     * @return Builder
     */
    public function getSchema() {
        if ($this->schema === null) {
            $this->schema = new Builder($this->getSchemaConnection());
        }

        return $this->schema;
    }

    /**
     * @return Connection
     */
    abstract protected function getSchemaConnection();
}