<?php

namespace Monkey\ImportSupport;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NilPortugues\Serializer\JsonSerializer;

/**
 * Class ResourceSettingDifference
 * @mixin \Eloquent
 * @package Monkey\ImportSupport
 * @author Lukáš Kielar
 */
class ResourceSettingDifference extends Model {
    use SoftDeletes;

    protected $connection = "mysql-master-app";
    protected $table = "resource_setting_difference";
    protected $fillable = ["resource_setting_id", "endpoint", "type", "active", "difference"];

    public function getDifferenceAttribute($value) {
        return $this->getSerializer()->unserialize($value)->__toString();
    }

    public function setDifferenceAttribute($value) {
        $this->attributes["difference"] = $this->getSerializer()->serialize($value);
    }

    /**
     * @param Builder $query
     * @param int $resourceSettingId
     * @return $this
     */
    public function scopeByResourceSettingId(Builder $query, int $resourceSettingId) {
        return $query->where("resource_setting_id", $resourceSettingId);
    }

    private function getSerializer() {
        return new JsonSerializer();
    }
}
