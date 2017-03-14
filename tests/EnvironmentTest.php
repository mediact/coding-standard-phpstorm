<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests;

use Composer\Composer;
use Composer\IO\IOInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Environment;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Environment
 */
class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Environment
     *
     * @covers ::__construct
     */
    public function testConstructor(): Environment
    {
        return new Environment(
            $this->createMock(FilesystemInterface::class),
            $this->createMock(FilesystemInterface::class),
            $this->createMock(FilesystemInterface::class),
            $this->createMock(IOInterface::class),
            $this->createMock(Composer::class)
        );
    }

    /**
     * @param Environment $environment
     *
     * @return void
     *
     * @depends testConstructor
     * @covers  ::getIdeConfigFilesystem
     */
    public function testGetIdeConfigFilesystem(Environment $environment)
    {
        $this->assertInstanceOf(
            FilesystemInterface::class,
            $environment->getIdeConfigFilesystem()
        );
    }

    /**
     * @param Environment $environment
     *
     * @return void
     *
     * @depends testConstructor
     * @covers  ::getDefaultsFilesystem
     */
    public function testGetDefaultsFilesystem(Environment $environment)
    {
        $this->assertInstanceOf(
            FilesystemInterface::class,
            $environment->getDefaultsFilesystem()
        );
    }

    /**
     * @param Environment $environment
     *
     * @return void
     *
     * @depends testConstructor
     * @covers  ::getProjectFilesystem
     */
    public function testGetRootFilesystem(Environment $environment)
    {
        $this->assertInstanceOf(
            FilesystemInterface::class,
            $environment->getProjectFilesystem()
        );
    }

    /**
     * @param Environment $environment
     *
     * @return void
     *
     * @depends testConstructor
     * @covers  ::getInputOutput
     */
    public function testGetInputOutput(Environment $environment)
    {
        $this->assertInstanceOf(
            IOInterface::class,
            $environment->getInputOutput()
        );
    }

    /**
     * @param Environment $environment
     *
     * @return void
     *
     * @depends testConstructor
     * @covers  ::getComposer
     */
    public function testGetComposer(Environment $environment)
    {
        $this->assertInstanceOf(
            Composer::class,
            $environment->getComposer()
        );
    }
}
