<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Foundation;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 * PinAdmin 服务提供者类
 */
class PinAdminServiceProvider extends ServiceProvider
{
    /**
     * PinAdmin 控制台命令
     *
     * @var array
     */
    protected array $commands = [];

    /**
     * PinAdmin 中间件
     *
     * @var array
     */
    protected array $middlewares = [];

    /**
     * 注册 PinAdmin 服务
     *
     * @return void
     */
    public function register()
    {
        $this->registerBinding();
    }

    /**
     * 启动 PinAdmin 服务
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()){
            $this->registerCommands();
            $this->loadMigrations();
        }

        if (!$this->app->routesAreCached()){
            $this->loadRoutes();
        }

        $this->registerMiddlewares();
        $this->loadViews();
        $this->configureAuthentication();
    }

    /**
     * 注册绑定容器
     *
     * @return void
     */
    private function registerBinding()
    {
        $this->app->singleton(PinAdmin::LABEL, PinAdmin::class);
    }

    /**
     * 注册控制台命令
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands($this->commands);
    }

    /**
     * 加载 PinAdmin 应用路由
     *
     * @return void
     */
    private function loadRoutes()
    {
        foreach (Admin::apps() as $app) {
            $this->loadRoutesFrom($app->path('routes/web.php'));
        }
    }

    /**
     * 加载 PinAdmin 应用数据库迁移文件
     *
     * @return void
     */
    private function loadMigrations()
    {
        foreach (Admin::apps() as $app) {
            $this->loadMigrationsFrom($app->path('migrations'));
        }
    }

    /**
     * 注册 PinAdmin 应用中间件
     *
     * @return void
     */
    private function registerMiddlewares()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        foreach ($this->middlewares as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * 加载 PinAdmin 应用视图
     *
     * @return void
     */
    private function loadViews()
    {
        $this->loadViewsFrom(Admin::packagePath('resources/views'), Admin::label());

        foreach (Admin::apps() as $app) {
            $this->loadViewsFrom($app->path('resources/views'), Admin::label($app->name(), '_'));
        }
    }

    /**
     * 配置 PinAdmin 应用的认证守卫和提供者
     *
     * @return void
     */
    private function configureAuthentication()
    {
        foreach (Admin::apps() as $app) {
            Config::set('auth.guards.' . $app->guard(), $app->config('auth_guard'));
            Config::set('auth.providers.' . $app->config('auth_guard.provider'), $app->config('auth_provider'));
        }
    }
}
