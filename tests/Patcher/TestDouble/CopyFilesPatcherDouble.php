<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher\TestDouble;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\CopyFilesTrait;

class CopyFilesPatcherDouble implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    /**
     * Patch the config.
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patch(
        EnvironmentInterface $environment
    ): void {
        $this->copyDirectory(
            $environment->getDefaultsFilesystem(),
            $environment->getIdeConfigFilesystem(),
            'foo'
        );
    }
}
