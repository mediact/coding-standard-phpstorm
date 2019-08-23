<?php declare(strict_types=1);
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
     * @param FilesystemInterface $source
     * @param FilesystemInterface $destination
     * @param string              $path
     *
     * @return void
     */
    private function copyDirectory(
        FilesystemInterface $source,
        FilesystemInterface $destination,
        string $path
    ): void {
        foreach ($source->listFiles($path) as $filePath) {
            $this->copyFile($source, $destination, $filePath);
        }
    }

    /**
     * Copy a file.
     *
     * @param FilesystemInterface $source
     * @param FilesystemInterface $destination
     * @param string              $path
     *
     * @return void
     */
    private function copyFile(
        FilesystemInterface $source,
        FilesystemInterface $destination,
        string $path
    ): void {
        $destination->put(
            $path,
            $source->read($path)
        );
    }
}
