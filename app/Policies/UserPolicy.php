<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 编辑更新个人资料的权限认证（只能更新自己的）
     */
    public function update(User $currentUser,User $user)
    {
      return $currentUser->id === $user->id;
    }

    /**
     * 删除用户
     */
    public function destroy(User $currentUser,User $user)
    {
      return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
