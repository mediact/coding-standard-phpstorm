<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\XmlAccessorInterface;

class FileTemplatesPatcher implements ConfigPatcherInterface
{
    use CopyFilesTrait;

    /**
     * @var XmlAccessorInterface
     */
    private $xmlAccessor;

    /**
     * Constructor.
     *
     * @param XmlAccessorInterface $xmlAccessor
     */
    public function __construct(XmlAccessorInterface $xmlAccessor)
    {
        $this->xmlAccessor = $xmlAccessor;
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
        $this->copyDirectory($configDir, $filesDir, 'fileTemplates');
        $this->patchWorkspaceConfig($configDir);
    }

    /**
     * Patch the workspace config.
     *
     * @param FilesystemInterface $configDir
     *
     * @return void
     */
    private function patchWorkspaceConfig(
        FilesystemInterface $configDir
    ) {
        if (!$configDir->has('workspace.xml')) {
            return;
        }
        $xml = simplexml_load_string($configDir->read('workspace.xml'));

        $node = $this->xmlAccessor->getDescendant(
            $xml,
            [
                ['component', ['name' => 'FileTemplateManagerImpl']],
                ['option', ['name' => 'SCHEME']]
            ]
        );

        $this->xmlAccessor->setAttributes(
            $node,
            ['value' => 'Project']
        );

        $configDir->put('workspace.xml', $xml->asXML());
    }
}
