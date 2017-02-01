<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;

class InspectionsPatcher implements ConfigPatcherInterface
{
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
        foreach ($filesDir->listContents('inspectionProfiles') as $filePath) {
            $configDir->put(
                $filePath,
                $filesDir->read($filePath)
            );
        }
    }
}
