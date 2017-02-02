<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\Tests\Patcher\TestDouble\CopyFilesPatcherDouble;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\CopyFilesTrait
 */
class CopyFilesTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return void
     *
     * @covers ::copyDirectory
     * @covers ::copyFile
     */
    public function testPatch()
    {
        $configDir = $this->createMock(FilesystemInterface::class);
        $configDir
            ->expects($this->exactly(2))
            ->method('put')
            ->withConsecutive(
                ['foo/foo.xml', '<foo/>'],
                ['foo/bar.xml', '<bar/>']
            );

        $filesDir = $this->createMock(FilesystemInterface::class);
        $filesDir
            ->expects($this->once())
            ->method('listFiles')
            ->with('foo')
            ->willReturn([
                'foo/foo.xml',
                'foo/bar.xml'
            ]);

        $filesDir
            ->expects($this->exactly(2))
            ->method('read')
            ->willReturnMap([
                ['foo/foo.xml', '<foo/>'],
                ['foo/bar.xml', '<bar/>']
            ]);

        (new CopyFilesPatcherDouble())->patch($configDir, $filesDir);
    }
}
