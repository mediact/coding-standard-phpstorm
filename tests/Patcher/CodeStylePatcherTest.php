<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Patcher\CodeStylePatcher;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\CodeStylePatcher
 */
class CodeStylePatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return void
     *
     * @covers ::patch
     */
    public function testPatch()
    {
        $configDir = $this->createMock(FilesystemInterface::class);
        $configDir
            ->expects($this->once())
            ->method('put')
            ->with('codeStyleSettings.xml', '<xml/>');

        $filesDir = $this->createMock(FilesystemInterface::class);
        $filesDir
            ->expects($this->once())
            ->method('read')
            ->with('codeStyleSettings.xml')
            ->willReturn('<xml/>');

        (new CodeStylePatcher())->patch($configDir, $filesDir);
    }
}
