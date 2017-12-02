<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array允许批量赋值的字段
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    /**
     * gravatar头像
     */
     public function gravatar($size = '100')
     {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
     }

     /**
      * 发送重置密码邮件
      */
      public function sendPasswordResetNotification($token)
      {
          $this->notify(new ResetPassword($token));
      }

    /**
     * 一个用户可以拥有多条微博
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 取出个人发布的微博按照创建时间排序
     */
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at','desc');
    }
}
