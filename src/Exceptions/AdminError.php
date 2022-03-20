<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Exceptions;

class AdminError
{
    const OK = 0;

    const ERROR = -1;

    const AUTH__USER_STATUS_ERROR = 900100;
    const AUTH__NAME_AND_PASSWORD_NOT_MATCHED = 900101;
}
