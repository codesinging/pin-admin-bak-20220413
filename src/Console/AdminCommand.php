<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Console;

use CodeSinging\PinAdmin\Foundation\PinAdmin;
use CodeSinging\PinAdmin\Support\Console\BaseCommand;

class AdminCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = PinAdmin::LABEL;

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = '显示 PinAdmin 扩展包信息';

    /**
     * The PinAdmin logo
     *
     * @var string
     */
    protected string $logo = <<<LOGO
      ____    ______  _   __   ___      ____    __  ___  ______  _   __
     / __ \  /_  __/ / | / /  /   |    / __ \  /  |/  / /_  __/ / | / /
    / /_/ /   / /   /  |/ /  / /| |   / / / / / /|_/ /   / /   /  |/ /
   / ____/ __/ /_  / /|  /  / ___ |  / /_/ / / /  / / __/ /_  / /|  /
  /_/     /_____/ /_/ |_/  /_/  |_| /_____/ /_/  /_/ /_____/ /_/ |_/
LOGO;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->outputLogo();
        $this->line('');
        $this->outputBrand();
        $this->line('');
        $this->call('admin:list');
    }

    protected function outputLogo(): void
    {
        $this->line($this->logo);
    }

    protected function outputBrand(): void
    {
        $this->title('PinAdmin 扩展包信息');
        $this->line(sprintf('  <info>%s</info>: v%s', PinAdmin::BRAND, PinAdmin::VERSION));
    }
}
