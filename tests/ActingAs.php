<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests;

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ActingAs
{
    /**
     * 创建并返回一个管理员用户
     *
     * @param string $username
     * @param bool $isSuper
     *
     * @return Model|User
     */
    protected function createUser(string $username, bool $isSuper = false): Model|User
    {
        $data = [
            'username' => $username,
            'password' => 'admin.123',
            'super' => $isSuper,
        ];
        return User::new()->create(User::new()->sanitize($data));
    }

    /**
     * 以一个管理员用户登录
     *
     * @param string|User $user
     * @param bool $isSuper
     *
     * @return mixed
     */
    protected function actingAsUser(string|User $user, bool $isSuper = false): static
    {
        is_string($user) and $user = $this->createUser($user, $isSuper);
        return $this->actingAs($user, Admin::guard());
    }
}
