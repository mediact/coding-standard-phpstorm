<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

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

        $filesDir = $this->createMock(FilesystemInterface::class);
        $filesDir
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $configDir = $this->createMock(FilesystemInterface::class);
        $configDir
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(true);

        $configDir
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

        $configDir
            ->expects($this->once())
            ->method('put')
            ->with('workspace.xml', $this->isType('string'));

        (new FileTemplatesPatcher($accessor))->patch(
            $configDir,
            $filesDir
        );
    }

    /**
     * @return void
     *
     * @covers ::patchWorkspaceConfig
     */
    public function testPatchNoWorkspace()
    {
        $accessor = $this->createMock(XmlAccessorInterface::class);
        $filesDir = $this->createMock(FilesystemInterface::class);
        $filesDir
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $configDir = $this->createMock(FilesystemInterface::class);
        $configDir
            ->expects($this->once())
            ->method('has')
            ->with('workspace.xml')
            ->willReturn(false);

        (new FileTemplatesPatcher($accessor))->patch(
            $configDir,
            $filesDir
        );
    }
}
