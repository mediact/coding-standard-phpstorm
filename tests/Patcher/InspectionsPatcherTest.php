<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\InspectionsPatcher;
use Mediact\CodingStandard\PhpStorm\XmlAccessorInterface;
use PHPUnit_Framework_TestCase;
use SimpleXMLElement;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\InspectionsPatcher
 */
class InspectionsPatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return InspectionsPatcher
     *
     * @covers ::__construct
     */
    public function testConstructor(): InspectionsPatcher
    {
        return new InspectionsPatcher(
            $this->createMock(XmlAccessorInterface::class)
        );
    }

    /**
     * @return void
     *
     * @covers ::patch
     * @covers ::setProjectProfiles
     */
    public function testPatch()
    {
        $accessor = $this->createMock(XmlAccessorInterface::class);

        $projectFs = $this->createMock(FilesystemInterface::class);
        $projectFs
            ->expects($this->once())
            ->method('has')
            ->with(InspectionsPatcher::PROJECT_PHPCS)
            ->willReturn(false);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getDefaultsFilesystem' => $defaultsFs,
                'getProjectFilesystem' => $projectFs
            ]
        );

        (new InspectionsPatcher($accessor))->patch($environment);
    }

    /**
     * @return void
     *
     * @covers ::setProjectProfiles
     * @covers ::setProjectPhpCsProfile
     */
    public function testPatchWithProjectProfile()
    {
        $child = new SimpleXMLElement('<some_data/>');

        $accessor = $this->createMock(XmlAccessorInterface::class);
        $accessor
            ->expects($this->once())
            ->method('getDescendant')
            ->with(
                $this->isInstanceOf(SimpleXMLElement::class),
                $this->isType('array')
            )
            ->willReturn($child);

        $projectFs = $this->createMock(FilesystemInterface::class);
        $projectFs
            ->expects($this->once())
            ->method('has')
            ->with(InspectionsPatcher::PROJECT_PHPCS)
            ->willReturn(true);

        $defaultsFs = $this->createMock(FilesystemInterface::class);
        $defaultsFs
            ->expects($this->once())
            ->method('listFiles')
            ->willReturn([]);

        $ideConfigFs = $this->createMock(FilesystemInterface::class);
        $ideConfigFs
            ->expects($this->once())
            ->method('read')
            ->with(InspectionsPatcher::INSPECTION_PROFILE)
            ->willReturn('<some_xml/>');

        $environment = $this->createConfiguredMock(
            EnvironmentInterface::class,
            [
                'getIdeConfigFilesystem' => $ideConfigFs,
                'getDefaultsFilesystem' => $defaultsFs,
                'getProjectFilesystem' => $projectFs
            ]
        );
        (new InspectionsPatcher($accessor))->patch($environment);
    }
}
