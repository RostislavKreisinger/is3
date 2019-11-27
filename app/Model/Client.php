<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Monkey\ImportSupport\Project as ImportSupportProject;

/**
 * Class Client
 *
 * @package App\Model
 * @property int $id
 * @property int|null $user_id
 * @property bool|null $active
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $address_id
 * @property int|null $company_id
 * @property int|null $businessnewssendingagree
 * @property int|null $clientregisterconditionagree
 * @property int|null $tariff_id
 * @property int|null $tariff_invoice_id
 * @property string|null $tariff_expired
 * @property string|null $subscription_expired - end of subscription
 * @property float|null $budget
 * @property int|null $remaining_orders
 * @property bool|null $subscription
 * @property int $free_zone
 * @property int $demo_zone =0 - hide demo; != 0 - show demo
 * @property string|null $date_created_first_project
 * @property int|null $low_credit_notif
 * @property string|null $logout_mail_key
 * @property string|null $last_backed_up
 * @property string|null $next_backup_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|ImportSupportProject[] $builderProjects
 * @property-read Collection|TariffOrder[] $builderTariffOrders
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|Client onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Client whereActive($value)
 * @method static Builder|Client whereAddressId($value)
 * @method static Builder|Client whereBudget($value)
 * @method static Builder|Client whereBusinessnewssendingagree($value)
 * @method static Builder|Client whereClientregisterconditionagree($value)
 * @method static Builder|Client whereCompanyId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereDateCreatedFirstProject($value)
 * @method static Builder|Client whereDeletedAt($value)
 * @method static Builder|Client whereDemoZone($value)
 * @method static Builder|Client whereEmail($value)
 * @method static Builder|Client whereFirstname($value)
 * @method static Builder|Client whereFreeZone($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereLastBackedUp($value)
 * @method static Builder|Client whereLastname($value)
 * @method static Builder|Client whereLogoutMailKey($value)
 * @method static Builder|Client whereLowCreditNotif($value)
 * @method static Builder|Client whereNextBackupDate($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereRemainingOrders($value)
 * @method static Builder|Client whereSubscription($value)
 * @method static Builder|Client whereSubscriptionExpired($value)
 * @method static Builder|Client whereTariffExpired($value)
 * @method static Builder|Client whereTariffId($value)
 * @method static Builder|Client whereTariffInvoiceId($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserId($value)
 * @method static QueryBuilder|Client withTrashed()
 * @method static QueryBuilder|Client withoutTrashed()
 * @mixin Eloquent
 */
class Client extends MasterModel {
    use SoftDeletes;
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
        return $this->hasMany(ImportSupportProject::class, 'user_id', 'user_id');
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
