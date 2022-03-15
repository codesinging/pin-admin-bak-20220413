<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Model;

use JetBrains\PhpStorm\Pure;

trait NewInstance
{
    /**
     * 使用静态方法新建一个模型实例
     *
     * @param array $attributes
     *
     * @return static
     */
    #[Pure]
    public static function new(array $attributes = []): static
    {
        return new static($attributes);
    }
}
