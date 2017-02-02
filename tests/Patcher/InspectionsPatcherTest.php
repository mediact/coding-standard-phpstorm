<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\InspectionsPatcher;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\InspectionsPatcher
 */
class InspectionsPatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return void
     *
     * @covers ::patch
     */
    public function testPatch()
    {
        $filesDir = $this->createMock(FilesystemInterface::class);
        $filesDir->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        (new InspectionsPatcher())->patch(
            $this->createMock(FilesystemInterface::class),
            $filesDir
        );
    }
}
