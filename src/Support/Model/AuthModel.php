<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Model;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class AuthModel extends User
{
    use HelperTraits;

    use Notifiable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->init();
    }

    /**
     * 初始化模型
     * @return void
     */
    protected function init()
    {

    }
}
