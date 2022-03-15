<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Factory
{
    /**
     * @var Application|null
     */
    protected ?Application $app;

    /**
     * @param string|Application|null $app
     */
    public function __construct(string|Application $app = null)
    {
        $app and $this->app = $this->getApp($app);
    }

    /**
     * 获取工厂实例
     *
     * @param string|Application|null $app
     *
     * @return static
     */
    public static function new(string|Application $app = null): static
    {
        return new static($app);
    }

    /**
     * 获取指定的应用
     *
     * @param string|Application $app
     *
     * @return Application
     */
    private static function getApp(string|Application $app): Application
    {
        return is_string($app) ? (new Application($app)) : $app;
    }

    /**
     * PinAdmin 应用根目录中是否存在指定应用的目录
     *
     * @param string|Application $app
     *
     * @return bool
     */
    public static function exists(string|Application $app): bool
    {
        return is_dir(self::getApp($app)->path());
    }

    /**
     * 已经启动的应用中是否存在指定的应用
     *
     * @param string|Application $app
     *
     * @return bool
     */
    public static function has(string|Application $app): bool
    {
        return array_key_exists(self::getApp($app)->name(), Admin::apps());
    }

    /**
     * 删除指定的 PinAdmin 应用
     *
     * @param string|Application $app
     *
     * @return void
     */
    public static function remove(string|Application $app)
    {
        $app = self::getApp($app);

        File::deleteDirectory($app->path());
        File::deleteDirectory($app->appPath());
        File::deleteDirectory($app->publicPath());

        Admin::reload();
    }

    /**
     * 删除所有的 PinAdmin 应用
     *
     * @return void
     */
    public static function clean()
    {
        File::deleteDirectory(Admin::rootPath());
        File::deleteDirectory(Admin::rootAppPath());
        File::deleteDirectory(Admin::rootPublicPath());

        Admin::reload();
    }

    /**
     * 返回当前工厂的 PinAdmin 应用实例
     *
     * @return Application|null
     */
    public function app(): ?Application
    {
        return $this->app ?? null;
    }

    /**
     * 应用文件存根中需要替换的标记
     *
     * @return array
     */
    private function replaceTags(): array
    {
        return [
            '__DUMMY_LABEL__' => Admin::label(),
            '__DUMMY_UPPER_LABEL__' => Str::upper(Admin::label()),
            '__DUMMY_NAME__' => $this->app->name(),
            '__DUMMY_STUDLY_NAME__' => $this->app->studlyName(),
            '__DUMMY_CAMEL_NAME__' => Str::camel($this->app->name()),
            '__DUMMY_UPPER_NAME__' => Str::upper($this->app->name()),
            '__DUMMY_GUARD__' => $this->app->guard(),
            '__DUMMY_NAMESPACE__' => $this->app->getNamespace(),
            '__DUMMY_DIST_PATH__' => $this->app->publicDirectory(),
            '__DUMMY_SRC_PATH__' => $this->app->directory('resources'),
            '__DUMMY_BASE_URL__' => $this->app->link(),
            '__DUMMY_HOME_URL__' => $this->app->link(),
            '__DUMMY_HOME_URL_WITH_DOMAIN__' => $this->app->url(),
            '__DUMMY_ASSET_URL__' => $this->app->asset(),
        ];
    }

    /**
     * 替换文件存根中的标记
     *
     * @param string $content
     *
     * @return string
     */
    public function replace(string $content): string
    {
        foreach ($this->replaceTags() as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    /**
     * 返回替换标记后的文件存根路径
     *
     * @param string ...$paths
     *
     * @return string
     */
    public function stubPath(string ...$paths): string
    {
        return $this->replace(Admin::packagePath('stubs', ...$paths));
    }

    /**
     * 创建目录
     *
     * @param ...$directories
     *
     * @return $this
     */
    public function makeDirectories(...$directories): static
    {
        foreach ($directories as $directory) {
            File::ensureDirectoryExists($directory);
        }
        return $this;
    }

    /**
     * 基于一个文件创建一个新文件
     *
     * @param string $stubFilename
     * @param string $destFilename
     *
     * @return $this
     */
    public function makeFile(string $stubFilename, string $destFilename): static
    {
        if (File::isFile($stubFilename)) {
            $this->makeDirectories(dirname($destFilename));

            File::put($this->replace($destFilename), $this->replace(File::get($stubFilename)));
        }
        return $this;
    }

    /**
     * 基于一个目录创建多个文件
     *
     * @param string $stubDirectory
     * @param string $destDirectory
     *
     * @return $this
     */
    public function makeFiles(string $stubDirectory, string $destDirectory): static
    {
        if (File::isDirectory($stubDirectory)) {
            $files = File::files($stubDirectory);
            foreach ($files as $file) {
                $this->makeFile($file->getPathname(), $destDirectory . DIRECTORY_SEPARATOR . $file->getFilename());
            }
        }
        return $this;
    }

    /**
     * 创建 PinAdmin 应用根目录
     *
     * @return $this
     */
    public function createRootDirectories(): static
    {
        $this->makeDirectories(
            Admin::rootPath(),
            Admin::rootAppPath(),
            Admin::rootPublicPath()
        );
        return $this;
    }

    /**
     * 创建 PinAdmin 应用基础目录、应用类目录、公共文件目录
     *
     * @return $this
     */
    public function createDirectories(): static
    {
        $this->makeDirectories(
            $this->app->path(),
            $this->app->appPath(),
            $this->app->publicPath()
        );
        return $this;
    }

    /**
     * 创建路由文件
     *
     * @return $this
     */
    public function createRoutes(): static
    {
        $this->makeFiles($this->stubPath('routes'), $this->app->path('routes'));
        return $this;
    }

    /**
     * 创建配置文件
     *
     * @return $this
     */
    public function createConfig(): static
    {
        $this->makeFiles($this->stubPath('config'), $this->app->path('config'));
        return $this;
    }

    /**
     * 创建模型文件
     *
     * @return $this
     */
    public function createModels(): static
    {
        $this->makeFiles($this->stubPath('models'), $this->app->appPath('Models'));
        return $this;
    }

    /**
     * 创建资源文件
     *
     * @return $this
     */
    public function createResources(): static
    {
        File::copyDirectory(Admin::packagePath('publish'), $this->app->path('resources'));
        return $this;
    }

    /**
     * 创建一个 PinAdmin 应用
     *
     * @return $this
     */
    public function create(): static
    {
        $this
            ->createRootDirectories()
            ->createDirectories()
            ->createRoutes()
            ->createConfig()
            ->createModels()
            ->createResources();

        return $this;
    }
}
