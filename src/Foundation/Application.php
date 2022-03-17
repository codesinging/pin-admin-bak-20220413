<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use Closure;
use CodeSinging\PinAdmin\Controllers\AuthController;
use CodeSinging\PinAdmin\Controllers\IndexController;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\View;
use JetBrains\PhpStorm\Pure;

/**
 * PinAdmin 应用类
 */
class Application
{
    /**
     * PinAdmin 应用名称
     *
     * @var string
     */
    protected string $name;

    /**
     * PinAdmin 应用名称，驼峰形式
     *
     * @var string
     */
    protected string $studlyName;

    /**
     * PinAdmin 应用用户认证守护者
     *
     * @var string
     */
    protected string $guard;

    /**
     * 应用配置实例
     *
     * @var Repository
     */
    protected Repository $config;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = Str::snake($name);
        $this->studlyName = Str::studly($name);
        $this->guard = $this->name;

        if (file_exists($file = $this->path('config', 'app.php'))) {
            $items = require $file;
        }

        $this->config = new Repository($items ?? []);
    }

    /**
     * 返回 PinAdmin 应用名称
     *
     * @param string|null $suffix
     * @param string $separator
     *
     * @return string
     */
    public function name(string $suffix = null, string $separator = '_'): string
    {
        return $this->name . ($suffix ? $separator . $suffix : '');
    }

    /**
     * 返回 PinAdmin 驼峰形式的应用名称
     *
     * @param string $suffix
     *
     * @return string
     */
    public function studlyName(string $suffix = ''): string
    {
        return $this->studlyName . Str::studly($suffix);
    }

    /**
     * 返回 PinAdmin 应用认证守护者
     *
     * @return string
     */
    public function guard(): string
    {
        return $this->guard;
    }

    /**
     * 返回 PinAdmin 应用基础目录或者其指定子目录
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function directory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, [PinAdmin::ROOT_DIRECTORY, $this->name, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用基础路径或指定的子路径
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function path(?string ...$paths): string
    {
        return base_path($this->directory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用的应用类文件目录或指定的子目录
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function appDirectory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, [PinAdmin::ROOT_APP_DIRECTORY, $this->studlyName, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用的应用类文件路径或指定子路径
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function appPath(?string ...$paths): string
    {
        return app_path($this->appDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用的公共文件目录或指定的子目录
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function publicDirectory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, ['public', PinAdmin::ROOT_PUBLIC_DIRECTORY, $this->name, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用的公共文件路径或指定子路径
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function publicPath(?string ...$paths): string
    {
        return base_path($this->publicDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用类命名空间
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function getNamespace(?string ...$paths): string
    {
        return implode('\\', ['App', str_replace('/', '\\', $this->appDirectory(...$paths))]);
    }

    /**
     * 设置或获取应用配置，或者返回应用实例
     *
     * @param array|string|null $key
     * @param mixed|null $default
     *
     * @return $this|array|Repository|mixed
     */
    public function config(array|string $key = null, mixed $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }
        if (is_array($key)) {
            $this->config->set($key);
            return $this;
        }
        return $this->config->get($key, $default);
    }

    /**
     * 返回 PinAdmin 应用路由前缀
     *
     * @return string
     */
    public function routePrefix(): string
    {
        return $this->config('route_prefix', $this->name());
    }

    /**
     * 返回 PinAdmin 应用的链接地址
     *
     * @param string|null $path
     * @param array $parameters
     * @param bool|null $secure
     *
     * @return string
     */
    public function url(string $path = null, array $parameters = [], bool $secure = null): string
    {
        $path = $this->routePrefix() . '/' . $path;
        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }

    /**
     * 获取 PinAdmin 应用的绝对链接地址
     *
     * @param string|null $path
     * @param array $parameters
     *
     * @return string
     */
    public function link(string $path = null, array $parameters = []): string
    {
        $link = '/' . $this->routePrefix();
        $path and $link .= Str::start($path, '/');
        $parameters and $link .= '?' . http_build_query($parameters);

        return $link;
    }

    /**
     * 返回当前应用的静态文件地址
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    #[Pure]
    public function asset(?string ...$paths): string
    {
        if (Str::startsWith($path = implode('/', $paths), ['https://', 'http://', '//', '/'])) {
            return $path;
        }

        return '/' . implode('/', [PinAdmin::ROOT_PUBLIC_DIRECTORY, $this->name, ...$paths]);
    }

    /**
     * 返回带版本号的静态资源文件路径
     *
     * @param string $path
     *
     * @return string
     * @throws Exception
     */
    public function mix(string $path): string
    {
        return mix($path, rtrim($this->asset(), '/'));
    }

    /**
     * 返回位于 PinAdmin 应用目录的模板名
     *
     * @param string $path
     *
     * @return string
     */
    #[Pure]
    public function template(string $path): string
    {
        return PinAdmin::LABEL . '_' . $this->name($path, '::');
    }

    /**
     * 返回位于 PinAdmin 应用目录的视图内容
     *
     * @param string|null $view
     * @param array $data
     * @param array $mergeData
     *
     * @return Factory|View
     */
    public function view(string $view = null, array $data = [], array $mergeData = []): Factory|View
    {
        empty($view) or $view = $this->template($view);
        return view($view, $data, $mergeData);
    }

    /**
     * 返回位于 PinAdmin 应用目录内的单文件组件内容
     *
     * @param string $path
     *
     * @return View|Factory
     */
    public function page(string $path): View|Factory
    {
        $path = str_replace('.', '/', $path);
        return $this->view('public/page', compact('path'));
    }

    /**
     * 返回 PinAdmin 认证实例
     *
     * @return Guard|StatefulGuard
     */
    public function auth(): Guard|StatefulGuard
    {
        return Auth::guard($this->guard());
    }

    /**
     * 返回 PinAdmin 认证用户
     *
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        return $this->auth()->user();
    }

    /**
     * 注册路由
     *
     * @param Closure $closure
     * @param bool $auth
     *
     * @return $this
     */
    public function routeGroup(Closure $closure, bool $auth = true): static
    {
        Route::prefix($this->routePrefix())
            ->middleware($auth ? $this->config('middlewares.auth', []) : $this->config('middlewares.guest', []))
            ->group(fn() => call_user_func($closure));

        return $this;
    }

    /**
     * 注册默认路由
     *
     * @return $this
     */
    public function defaultRoutes(): static
    {
        $this->routeGroup(function () {
            Route::get('/', [IndexController::class, 'index'])->name($this->name('index', '.'));
        });
        $this->routeGroup(function () {
            Route::get('auth', [AuthController::class, 'index'])->name($this->name('auth', '.'));
        }, false);
        return $this;
    }
}
