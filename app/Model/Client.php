<?php

namespace App\Model;

use Eloquent;

class Client extends Eloquent {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'client';
    
    protected $guarded = [];
    
    /**
     *
     * @var ImportSupportProject 
     */
    protected $projects;
    
    /**
     *
     * @var Tariff 
     */
    protected $tariff;
    
    /**
     *
     * @var Tariff 
     */
    protected $tariffOrders;
    
    
    /**
     * 
     * @return ImportSupportProject[]
     */
    public function getProjects() {
        if($this->projects === null){
            $this->projects = $this->builderProjects()->get();
        }
        return $this->projects;
    }
    
    public function builderProjects() {
        return $this->hasMany(ImportSupportProject::class, 'user_id');
    }
    
    /**
     * 
     * @return Tariff
     */
    public function getTariff() {
        if($this->tariff === null){
            $this->tariff = Tariff::find($this->tariff_id);
        }
        return $this->tariff;
    }
    
    public function getTariffOrders() {
        if($this->tariffOrders === null){
            $this->tariffOrders = $this->builderTariffOrders()->orderBy('created_at', 'DESC')->get();
        }
        return $this->tariffOrders;
    }
    
    public function builderTariffOrders() {
        return $this->hasManyThrough(TariffOrder::class, TariffInvoice::class);
    }
    
    

}
