<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\XmlAccessorInterface;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Patcher\FileTemplatesPatcher;
use SimpleXMLElement;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\FileTemplatesPatcher
 */
class FileTemplatesPatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return FileTemplatesPatcher
     *
     * @covers ::__construct
     */
    public function testConstructor(): FileTemplatesPatcher
    {
        return new FileTemplatesPatcher(
            $this->createMock(XmlAccessorInterface::class)
        );
    }

    /**
     * @return void
     *
     * @covers ::patch
     * @covers ::patchWorkspaceConfig
     */
    public function testPatch()
    {
        $accessor = $this->createMock(XmlAccessorInterface::class);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(true);

        $ideConfigFs
            ->expects($this->once())
            ->method('read')
            ->with('workspace.xml')
            ->willReturn('<config/>');

        $child = new SimpleXMLElement('<some_data/>');
        $accessor->expects($this->once())
            ->method('getDescendant')
            ->with(
                $this->isInstanceOf(SimpleXMLElement::class),
                $this->isType('array')
            )
            ->willReturn($child);

        $accessor
            ->expects($this->once())
            ->method('setAttributes')
            ->with(
                $child,
                $this->isType('array')
            );

        $ideConfigFs
            ->expects($this->once())
            ->method('put')
            ->with('workspace.xml', $this->isType('string'));

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs
            ]
        );

        (new FileTemplatesPatcher($accessor))->patch($environment);
    }

    /**
     * @return void
     *
     * @covers ::patchWorkspaceConfig
     */
    public function testPatchNoWorkspace()
    {
        $accessor   = $this->createMock(XmlAccessorInterface::class);
        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(false);

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs
            ]
        );

        (new FileTemplatesPatcher($accessor))->patch($environment);
    }
}
