<?php

namespace App\Model;

use Monkey\ImportSupport\Project as ImportSupportProject;

/**
 * App\Model\User
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property bool|null $activated
 * @property int $verified_email
 * @property string|null $token
 * @property string|null $activated_at
 * @property string|null $lastlogin_at
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $photo
 * @property string|null $phone
 * @property string|null $phone_prefix
 * @property string|null $passwordresetcode
 * @property string|null $language
 * @property bool|null $suspended
 * @property int|null $suspended_by
 * @property string|null $suspended_at
 * @property int $createdprojects
 * @property int|null $default_project_id
 * @property int|null $crm_user_id
 * @property string|null $crm_description
 * @property bool|null $crm_show
 * @property string $crm_status_code
 * @property bool|null $crm_vip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool|null $setup_done
 * @property int|null $pin
 * @property string|null $ip_address
 * @property bool|null $test_user 1 - is test user
 * @property bool|null $is_admin
 * @property bool|null $is_agency
 * @property int|null $agency_id
 * @property string|null $agency_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Monkey\ImportSupport\Project[] $builderProjects
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereActivatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereAgencyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCreatedprojects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCrmDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCrmShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCrmStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCrmUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereCrmVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereDefaultProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereIsAgency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereLastloginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePasswordresetcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePhonePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User wherePin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereSetupDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereSuspendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereSuspendedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereTestUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\User whereVerifiedEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'user';
    
    protected $guarded = [];
    
    /**
     *
     * @var ImportSupportProject 
     */
    protected $projects;
    
    /**
     *
     * @var Client 
     */
    protected $client;
    
    
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
     * @return Client
     */
    public function getClient() {
        if($this->client === null){
            $this->client = $this->hasMany(Client::class, 'user_id')->first();
        }
        return $this->client;
    }
    
}
