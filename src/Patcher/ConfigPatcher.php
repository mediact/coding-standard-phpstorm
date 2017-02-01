<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\XmlAccessor;

class ConfigPatcher implements ConfigPatcherInterface
{
    /**
     * @var ConfigPatcherInterface[]
     */
    private $patchers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $xmlAccessor = new XmlAccessor();

        $this->patchers = [
            new CodeStylePatcher(),
            new FileTemplatesPatcher($xmlAccessor),
            new InspectionsPatcher()
        ];
    }

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
        foreach ($this->patchers as $patcher) {
            $patcher->patch($configDir, $filesDir);
        }
    }
}
