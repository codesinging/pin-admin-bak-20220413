<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Console;

use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\PinAdmin;
use CodeSinging\PinAdmin\Support\Console\BaseCommand;
use Illuminate\Support\Str;

class CreateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = PinAdmin::LABEL . ':create {name}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = '创建 PinAdmin 应用';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->title('创建 PinAdmin 应用......');

        if ($this->verify($name = Str::snake($this->argument('name')))) {
            if (!Factory::exists($name)) {
                Factory::new($name)->create();

                $this->info(sprintf('创建 PinAdmin 应用[%s]成功', $name));
            } else {
                $this->error(sprintf('应用[%s]已经存在', $name));
            }
        } else {
            $this->error(sprintf('应用名称[%s]非法', $name));
        }
    }

    /**
     * 验证应用名是否合法
     *
     * @param string $name
     *
     * @return bool
     */
    private function verify(string $name): bool
    {
        return !empty($name) && preg_match('/^[a-z]+[a-z0-9_]*$/', $name) === 1;
    }
}
