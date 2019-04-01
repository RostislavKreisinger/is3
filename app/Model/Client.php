<?php

namespace App\Model;

use Eloquent;
use Monkey\ImportSupport\Project as ImportSupportProject;

/**
 * App\Model\Client
 *
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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Monkey\ImportSupport\Project[] $builderProjects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\TariffOrder[] $builderTariffOrders
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Client onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereBusinessnewssendingagree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereClientregisterconditionagree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereDateCreatedFirstProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereDemoZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereFreeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereLastBackedUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereLogoutMailKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereLowCreditNotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereNextBackupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereRemainingOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereSubscriptionExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereTariffExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereTariffInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Client whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Client withoutTrashed()
 * @mixin \Eloquent
 */
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
