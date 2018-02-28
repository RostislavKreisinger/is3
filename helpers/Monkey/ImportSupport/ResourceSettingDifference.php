<?php

namespace Monkey\ImportSupport;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\ImportEshopDataObjects\Entity\Simple\Alias;
use Monkey\ImportEshopDataObjects\Entity\Simple\Condition;
use Monkey\ImportEshopDataObjects\Entity\Simple\Join;
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

    /**
     * Sets the current difference to active
     * Active differences are used in both debug calls and live Import Flow (unless they are deleted)
     */
    public function activate() {
        $this->update(['active' => true]);
    }

    /**
     * Sets the current difference to inactive
     * Inactive differences are used only in debug calls (unless they are deleted)
     */
    public function deactivate() {
        $this->update(['active' => false]);
    }

    /**
     * Unserializes the difference after retrieving it from DB
     * Differences are saved to DB as serialized Alias, Join or Condition objects from Monkey\ImportEshopDataObjects package
     * @param $value
     * @return Alias|Join|Condition
     */
    public function getDifferenceAttribute($value) {
        return $this->getSerializer()->unserialize($value);
    }

    /**
     * Serializes the difference before saving it to DB
     * Differences are saved to DB as serialized Alias, Join or Condition objects from Monkey\ImportEshopDataObjects package
     * @param Alias|Condition $value
     */
    public function setDifferenceAttribute($value) {
        $this->attributes["difference"] = $this->getSerializer()->serialize($value);
    }

    /**
     * @param Builder $query
     * @param int $resourceSettingId
     * @return $this
     */
    public function scopeByResourceSettingId(Builder $query, int $resourceSettingId) {
        return $query->where("resource_setting_id", "=", $resourceSettingId);
    }

    /**
     * @param Builder $query
     * @param string $endpoint
     * @return $this
     */
    public function scopeByEndpoint(Builder $query, string $endpoint) {
        return $query->where("endpoint", "=", $endpoint);
    }

    /**
     * @param Builder $query
     * @return $this
     */
    public function scopeActive(Builder $query) {
        return $query->where("active", "=", true);
    }

    /**
     * @param Builder $query
     * @return $this
     */
    public function scopeInactive(Builder $query) {
        return $query->where("active", "=", false);
    }

    /**
     * @return JsonSerializer
     */
    private function getSerializer() {
        return new JsonSerializer();
    }
}
