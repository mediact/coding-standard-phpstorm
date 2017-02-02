<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;

trait CopyFilesTrait
{
    /**
     * Copy a directory.
     *
     * @param FilesystemInterface $configDir
     * @param FilesystemInterface $filesDir
     * @param string              $path
     *
     * @return void
     */
    protected function copyDirectory(
        FilesystemInterface $configDir,
        FilesystemInterface $filesDir,
        $path
    ) {
        foreach ($filesDir->listFiles($path) as $filePath) {
            $this->copyFile($configDir, $filesDir, $filePath);
        }
    }

    /**
     * Copy a file.
     *
     * @param FilesystemInterface $configDir
     * @param FilesystemInterface $filesDir
     * @param string              $path
     *
     * @return void
     */
    protected function copyFile(
        FilesystemInterface $configDir,
        FilesystemInterface $filesDir,
        $path
    ) {
        $configDir->put(
            $path,
            $filesDir->read($path)
        );
    }
}
