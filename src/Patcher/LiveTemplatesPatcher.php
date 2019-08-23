<?php declare(strict_types=1);
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;

class LiveTemplatesPatcher implements ConfigPatcherInterface
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
        if (! empty($environment->getIdeDefaultConfigFilesystem()->getRoot())) {
            $this->copyDirectory(
                $environment->getDefaultsFilesystem(),
                $environment->getIdeDefaultConfigFilesystem(),
                'templates'
            );
        }
    }
}
