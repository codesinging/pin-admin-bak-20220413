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
use Illuminate\Support\Facades\File;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * PinAdmin 应用管理器类
 *
 * @method string name(string $suffix = null, string $separator = '_')
 * @method string studlyName(string $suffix = '')
 * @method string guard()
 * @method string directory(?string ...$paths)
 * @method string path(?string ...$paths)
 * @method string appDirectory(?string ...$paths)
 * @method string appPath(?string ...$paths)
 * @method string publicDirectory(?string ...$paths)
 * @method string publicPath(?string ...$paths)
 * @method string getNamespace(?string ...$paths)
 * @method Application|array|Repository|mixed config(array|string $key = null, mixed $default = null)
 * @method string routePrefix()
 * @method string url(string $path = null, array $parameters = [], bool $secure = null)
 * @method string link(string $path = null, array $parameters = [])
 * @method string asset(?string ...$paths)
 * @method string mix(string $path)
 * @method string template(string $path)
 * @method string packageTemplate(string $path)
 * @method Factory|View view(string $view = null, array $data = [], array $mergeData = [])
 * @method Factory|View packageView(string $view = null, array $data = [], array $mergeData = [])
 * @method Factory|View page(string $path)
 * @method Factory|View packagePage(string $path)
 * @method Guard|StatefulGuard auth()
 * @method Authenticatable|null user()
 * @method string routeName(string $name)
 * @method Application routeGroup(Closure $closure, bool $auth = true)
 */
class PinAdmin
{
    /**
     * PinAdmin 版本号
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * PinAdmin 品牌名称
     */
    const BRAND = 'PinAdmin';

    /**
     * PinAdmin 标记
     */
    const LABEL = 'admin';

    /**
     * PinAdmin 应用根目录
     */
    const ROOT_DIRECTORY = 'admins';

    /**
     * PinAdmin 应用类文件根目录
     */
    const ROOT_APP_DIRECTORY = 'Admins';

    /**
     * PinAdmin 应用公共文件根目录
     */
    const ROOT_PUBLIC_DIRECTORY = 'admins';

    /**
     * 所有 PinAdmin 应用
     *
     * @var Application[]
     */
    protected array $apps = [];

    /**
     * 当前启动的 PinAdmin 应用
     *
     * @var ?Application
     */
    protected ?Application $app = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * 返回 PinAdmin 版本号
     *
     * @return string
     */
    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * 返回 PinAdmin 的品牌名
     *
     * @return string
     */
    public function brand(): string
    {
        return self::BRAND;
    }

    /**
     * 返回 PinAdmin 标记及以该标记为前缀的字符串
     *
     * @param string|null $suffix
     * @param string $separator
     *
     * @return string
     */
    public function label(string $suffix = null, string $separator = '_'): string
    {
        return self::LABEL . ($suffix ? $separator . $suffix : '');
    }

    /**
     * 返回 PinAdmin 应用根目录或指定子目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootDirectory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::ROOT_DIRECTORY, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootPath(?string ...$paths): string
    {
        return base_path(self::rootDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用类文件根目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootAppDirectory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::ROOT_APP_DIRECTORY, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用类文件根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootAppPath(?string ...$paths): string
    {
        return app_path(self::rootAppDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 应用公共文件根目录
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootPublicDirectory(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, ['public', self::ROOT_PUBLIC_DIRECTORY, ...$paths]);
    }

    /**
     * 返回 PinAdmin 应用公共文件根路径或指定子路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function rootPublicPath(?string ...$paths): string
    {
        return base_path(self::rootPublicDirectory(...$paths));
    }

    /**
     * 返回 PinAdmin 包路径
     *
     * @param string|null ...$paths
     *
     * @return string
     */
    public function packagePath(?string ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 2), ...$paths]);
    }

    /**
     * 加载应用
     *
     * @return $this
     */
    public function load(): static
    {
        $this->apps = [];
        if (File::isDirectory($this->rootPath())) {
            $directories = File::directories($this->rootPath());
            foreach ($directories as $directory) {
                $application = new Application(basename($directory));
                $this->apps[$application->name()] = $application;
            }
        }
        return $this;
    }

    /**
     * 启动 PinAdmin 应用
     *
     * @param string $name
     *
     * @return $this
     */
    public function boot(string $name): static
    {
        if (array_key_exists($name, $this->apps)) {
            $this->app = $this->apps[$name];
        }

        return $this;
    }

    /**
     * 返回所有 PinAdmin 应用
     *
     * @return Application[]
     */
    public function apps(): array
    {
        return $this->apps;
    }

    /**
     * 返回当前或指定的 PinAdmin 应用
     *
     * @param string|null $name
     *
     * @return Application|null
     */
    public function app(string $name = null): ?Application
    {
        return is_null($name) ? $this->app : ($this->apps[$name] ?? $this->app);
    }

    /**
     * 调用当前 PinAdmin 应用的方法
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->app->$name(...$arguments);
    }
}
