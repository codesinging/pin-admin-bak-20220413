<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Controllers;

use CodeSinging\PinAdmin\Exceptions\AdminError;
use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Support\Routing\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function index()
    {
        return csrf_token();
    }

    /**
     * 用户登录
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username' => '登录账号不能为空',
            'password' => '登录密码不能为空'
        ]);

        if (Admin::auth()->attempt($credentials)) {
            if (!Admin::user()['status']) {
                return $this->error('用户状态异常', AdminError::AUTH__USER_STATUS_ERROR);
            }

            $request->session()->regenerate();

            return $this->success('登录成功');
        }

        return $this->error('账号和密码不匹配', AdminError::AUTH__NAME_AND_PASSWORD_NOT_MATCHED);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Admin::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success('退出登录成功');
    }

    /**
     * 获取认证用户
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success('获取认证用户成功', $user);
    }
}
