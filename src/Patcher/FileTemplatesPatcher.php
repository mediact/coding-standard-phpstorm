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
        $this->copyFiles($configDir, $filesDir);
        $this->patchWorkspaceConfig($configDir);
    }

    /**
     * Copy files.
     *
     * @param FilesystemInterface $configDir
     * @param FilesystemInterface $filesDir
     *
     * @return void
     */
    private function copyFiles(
        FilesystemInterface $configDir,
        FilesystemInterface $filesDir
    ) {
        foreach ($filesDir->listFiles('fileTemplates') as $filePath) {
            $configDir->put(
                $filePath,
                $filesDir->read($filePath)
            );
        }

        if (!$configDir->has('workspace.xml')) {
            $configDir->put(
                'workspace.xml',
                $filesDir->read('emptyConfig.xml')
            );
        }
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
