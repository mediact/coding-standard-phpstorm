<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;

class CodeStylePatcher implements ConfigPatcherInterface
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
    ) {
        $this->copyFile(
            $environment->getDefaultsFilesystem(),
            $environment->getIdeConfigFilesystem(),
            'codeStyleSettings.xml'
        );
    }
}
