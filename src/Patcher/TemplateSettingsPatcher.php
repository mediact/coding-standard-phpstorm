<?php declare(strict_types=1);
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\XmlAccessorInterface;

class TemplateSettingsPatcher implements ConfigPatcherInterface
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
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patch(
        EnvironmentInterface $environment
    ): void {
        $this->patchFileTemplateSettings(
            $environment->getIdeConfigFilesystem(),
            $environment
        );
    }

    /**
     * Patch file template settings if exists otherwise create one.
     *
     * @param FilesystemInterface  $ideConfigFs
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patchFileTemplateSettings(
        FilesystemInterface $ideConfigFs,
        EnvironmentInterface $environment
    ): void {
        if (!$ideConfigFs->has('file.template.settings.xml')) {
            $this->copyFile(
                $environment->getDefaultsFilesystem(),
                $environment->getIdeConfigFilesystem(),
                'file.template.settings.xml'
            );
        } else {
            $xml = simplexml_load_string(
                $ideConfigFs->read('file.template.settings.xml')
            );

            foreach ($this->getFileTemplates() as $xmlTag => $fileTemplateNames) {
                foreach ($fileTemplateNames as $fileTemplateName) {
                    $node = $this->xmlAccessor->getDescendant(
                        $xml,
                        [
                            [
                                'component',
                                ['name' => 'ExportableFileTemplateSettings']
                            ],
                            [$xmlTag],
                            ['template', ['name' => $fileTemplateName]]
                        ]
                    );
                    $this->xmlAccessor->setAttributes(
                        $node,
                        [
                            'reformat' => 'false',
                            'live-template-enabled' => 'true'
                        ]
                    );
                    $ideConfigFs->put('file.template.settings.xml', $xml->asXML());
                }
            }
        }
    }

    /**
     * Enable file templates
     *
     * @return array
     */
    public function getFileTemplates(): array
    {
        return [
            'default_templates' => [
                'M2-Acl XML.xml',
                'M2-Class.php',
                'M2-Class-Block.php',
                'M2-Class-Helper.php',
                'M2-Class-Observer.php',
                'M2-Class-ViewModel.php',
                'M2-Config-XML.xml',
                'M2-Db-schema-XML.xml',
                'M2-DI.xml',
                'M2-Extension-Attributes-XML.xml',
                'M2-Layout-XML.xml',
                'M2-Module-XML.xml',
                'M2-Registration.php',
                'M2-Sales-XML.xml',
                'M2-System-include-XML.xml',
                'M2-System-XML.xml'
            ],
            'includes_templates' => [
                'M2-PHP-File-Header.php',
                'M2-Settings.php',
                'M2-XML-File-Header.xml',
            ]
        ];
    }
}
