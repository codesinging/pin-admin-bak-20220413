<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace Tests;

use Illuminate\Support\Facades\File;

trait Backup
{
    protected array $backupFiles = [
        'composer.json',
        'package.json',
    ];

    protected string $backupSuffix = '.test.bak';

    /**
     * 备份文件
     *
     * @return void
     */
    protected function backup()
    {
        foreach ($this->backupFiles as $file) {
            $srcFilename = base_path($file);
            if (file_exists($srcFilename)) {
                $backupFilename = $srcFilename . $this->backupSuffix;
                File::copy($srcFilename, $backupFilename);
            }
        }
    }

    /**
     * 恢复文件
     *
     * @return void
     */
    protected function restore()
    {
        foreach ($this->backupFiles as $file) {
            $srcFilename = base_path($file);
            $backupFilename = $srcFilename . $this->backupSuffix;

            if (file_exists($backupFilename)) {
                File::copy($backupFilename, $srcFilename);
            } else {
                File::delete($srcFilename);
            }
        }
    }
}
