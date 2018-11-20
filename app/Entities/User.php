<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entities;

use Cache;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use RevisionableTrait, EntrustUserTrait {
        EntrustUserTrait::boot insteadof RevisionableTrait;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret_key'
    ];

    /**
     * Returns if a user has enabled two factor authentication.
     *
     * @return bool
     */
    public function getHasTwoFactorAttribute()
    {
        return trim($this->google2fa_secret_key) !== '';
    }

    /**
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * recordLastActivatedAt
     */
    public function recordLastActivatedAt()
    {
        $now = Carbon::now()->toDateTimeString();

        // 这个 Redis 用于数据库更新，数据库每同步一次则清空一次该 Redis 。
        $update_key = 'activated_time_for_update';
        $update_data = Cache::get($update_key);
        $update_data[$this->id] = $now;
        Cache::forever($update_key, $update_data);

        // 这个 Redis 用于读取，每次要获取活跃时间时，先到该 Redis 中获取数据。
        $show_key = 'activated_time_data';
        $show_data = Cache::get($show_key);
        $show_data[$this->id] = $now;
        Cache::forever($show_key, $show_data);
    }

    /**
     * @return string
     */
    public function lastActivatedAt()
    {
        $show_key = 'activated_time_data';
        $show_data = Cache::get($show_key);

        // 如果 Redis 中没有，则从数据库里获取，并同步到 Redis 中
        if (!isset($show_data[$this->id])) {
            $show_data[$this->id] = $this->last_activated_at;
            Cache::forever($show_key, $show_data);
        }

        return $show_data[$this->id];
    }

    /**
     * @return mixed
     */
    public function routeNotificationForPushover()
    {
        if ($this->id == 1) {
            return env('PUSHOVER_USER_KEY');
        }
        return null;
    }

}
