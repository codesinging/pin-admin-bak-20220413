<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Routing;

use CodeSinging\PinAdmin\Exceptions\AdminError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

trait ApiResponses
{
    /**
     * 返回正确的 json 响应
     *
     * @param Model|array|string|Collection|null $message
     * @param Model|array|Collection|null $data
     *
     * @return JsonResponse
     */
    protected function success(Model|array|string|Collection $message = null, Model|array|Collection $data = null): JsonResponse
    {
        return ApiResponse::success($message, $data);
    }

    /**
     * 返回错误的 json 响应
     *
     * @param string|null $message
     * @param int $code
     * @param mixed|null $data
     *
     * @return JsonResponse
     */
    protected function error(string $message = null, int $code = AdminError::ERROR, mixed $data = null): JsonResponse
    {
        return ApiResponse::error($message, $code, $data);
    }
}
