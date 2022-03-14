<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * PinAdmin 应用管理器门面类
 *
 * @method static string version()
 * @method static string brand()
 * @method static string label(string $suffix = null, string $separator = '_')
 * @method static string rootDirectory(?string ...$paths)
 * @method static string rootPath(?string ...$paths)
 * @method static string rootAppDirectory(?string ...$paths)
 * @method static string rootAppPath(?string ...$paths)
 * @method static string rootPublicDirectory(?string ...$paths)
 * @method static string rootPublicPath(?string ...$paths)
 * @method static string packagePath(?string ...$paths)
 * @method static PinAdmin reload()
 * @method static PinAdmin boot(string $name)
 * @method static Application[] apps()
 * @method static Application app(string $name = null)
 *
 * @method static string name(string $suffix = null, string $separator = '_')
 * @method static string studlyName(string $suffix = '')
 * @method static string guard()
 * @method static string directory(?string ...$paths)
 * @method static string path(?string ...$paths)
 * @method static string appDirectory(?string ...$paths)
 * @method static string appPath(?string ...$paths)
 * @method static string publicDirectory(?string ...$paths)
 * @method static string publicPath(?string ...$paths)
 * @method static string getNamespace(?string ...$paths)
 * @method static Application|array|Repository|mixed config(array|string $key = null, mixed $default = null)
 * @method static string routePrefix()
 * @method static string url(string $path = null, array $parameters = [], bool $secure = null)
 * @method static string link(string $path = null, array $parameters = [])
 * @method static string asset(?string ...$paths)
 * @method static string mix(string $path)
 * @method static string template(string $path)
 * @method static Factory|View view(string $view = null, array $data = [], array $mergeData = [])
 * @method static Factory|View page(string $path)
 * @method static Guard|StatefulGuard auth()
 * @method static Authenticatable|null user()
 * @method static Application routeGroup(Closure $closure, bool $auth = true)
 * @method static Application defaultRoutes()
 *
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PinAdmin::LABEL;
    }
}
