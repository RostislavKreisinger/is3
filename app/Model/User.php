<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * App\Model\User
 *
 * @property int $id
 * @property int|null $client_id
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool|null $setup_done
 * @property int|null $pin
 * @property string|null $ip_address
 * @property bool|null $test_user 1 - is test user
 * @property bool|null $is_admin
 * @property bool|null $is_agency
 * @property int|null $agency_id
 * @property string|null $agency_url
 * @property-read Client $client
 * @property-read Collection|Project[] $projects
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|User onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|User whereActivated($value)
 * @method static Builder|User whereActivatedAt($value)
 * @method static Builder|User whereAgencyId($value)
 * @method static Builder|User whereAgencyUrl($value)
 * @method static Builder|User whereClientId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCreatedprojects($value)
 * @method static Builder|User whereCrmDescription($value)
 * @method static Builder|User whereCrmShow($value)
 * @method static Builder|User whereCrmStatusCode($value)
 * @method static Builder|User whereCrmUserId($value)
 * @method static Builder|User whereCrmVip($value)
 * @method static Builder|User whereDefaultProjectId($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereFirstname($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIpAddress($value)
 * @method static Builder|User whereIsAdmin($value)
 * @method static Builder|User whereIsAgency($value)
 * @method static Builder|User whereLanguage($value)
 * @method static Builder|User whereLastloginAt($value)
 * @method static Builder|User whereLastname($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePasswordresetcode($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePhonePrefix($value)
 * @method static Builder|User wherePhoto($value)
 * @method static Builder|User wherePin($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSetupDone($value)
 * @method static Builder|User whereSuspended($value)
 * @method static Builder|User whereSuspendedAt($value)
 * @method static Builder|User whereSuspendedBy($value)
 * @method static Builder|User whereTestUser($value)
 * @method static Builder|User whereToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereVerifiedEmail($value)
 * @method static QueryBuilder|User withTrashed()
 * @method static QueryBuilder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends MasterModel {
    use SoftDeletes;

    protected $table = 'user';
    protected $guarded = [];

    /**
     * @return HasOne
     */
    public function client(): HasOne {
        return $this->hasOne(Client::class);
    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany {
        return $this->hasMany(Project::class);
    }
}
