<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class AdminException extends Exception
{
    #[Pure]
    public function __construct(string $message = "", int $code = AdminError::ERROR)
    {
        parent::__construct($message, $code);
    }
}
