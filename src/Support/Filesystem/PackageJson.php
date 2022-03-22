<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Filesystem;

class PackageJson extends JsonFile
{
    /**
     * @param string|null $filename
     */
    public function __construct(string $filename = null)
    {
        $filename = $filename ?: base_path('package.json');
        parent::__construct($filename);
    }

    /**
     * 添加命令
     *
     * @param array $scripts
     *
     * @return PackageJson
     */
    public function addScripts(array $scripts): static
    {
        $this->merge('scripts', $scripts)->write();
        return $this;
    }

    /**
     * 添加开发依赖
     *
     * @param array $devDependencies
     *
     * @return PackageJson
     */
    public function addDevDependencies(array $devDependencies): static
    {
        $this->merge('devDependencies', $devDependencies)->write();
        return $this;
    }

    /**
     * 添加依赖
     *
     * @param array $dependencies
     *
     * @return PackageJson
     */
    public function addDependencies(array $dependencies): static
    {
        $this->merge('dependencies', $dependencies)->write();
        return $this;
    }
}
