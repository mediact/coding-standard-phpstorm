<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;

class CodeStylePatcher implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    /**
     * Patch the config.
     *
     * @param FilesystemInterface $configDir
     * @param FilesystemInterface $filesDir
     *
     * @return void
     */
    public function patch(
        FilesystemInterface $configDir,
        FilesystemInterface $filesDir
    ) {
        $this->copyFile($configDir, $filesDir, 'codeStyleSettings.xml');
    }
}