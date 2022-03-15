<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Console;

use CodeSinging\PinAdmin\Foundation\PinAdmin;
use CodeSinging\PinAdmin\Support\Console\BaseCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class ListCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = PinAdmin::LABEL . ':list';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = '列出所有 PinAdmin 命令行命令';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->listCommands();
    }

    /**
     * @return array
     */
    protected function getCommands(): array
    {
        return collect(Artisan::all())->mapWithKeys(function ($command, $key) {
            if (PinAdmin::LABEL === $key || Str::startsWith($key, PinAdmin::LABEL . ':')) {
                return [$key => $command];
            }
            return [];
        })->toArray();
    }

    /**
     * @param array $commands
     *
     * @return int
     */
    #[Pure]
    protected function getNameColumnWidth(array $commands): int
    {
        $widths = [];

        /** @var Command $command */
        foreach ($commands as $command) {
            $widths[] = Str::length($command->getName());
        }

        return $widths ? max($widths) + 2 : 0;
    }

    /**
     * List all the PinAdmin commands
     */
    protected function listCommands(): void
    {
        $this->title('PinAdmin 命令行命令列表:');

        $commands = $this->getCommands();

        $width = $this->getNameColumnWidth($commands);

        /** @var Command $command */
        foreach ($commands as $command) {
            $this->line(sprintf(" <info>%-{$width}s</info> %s", $command->getName(), $command->getDescription()));
        }

        $this->line('');
    }
}
