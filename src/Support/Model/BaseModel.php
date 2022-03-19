<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HelperTraits;

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
