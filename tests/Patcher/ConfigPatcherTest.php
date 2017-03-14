<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcher;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcher
 */
class ConfigPatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return ConfigPatcher
     *
     * @covers ::__construct
     */
    public function testConstructor(): ConfigPatcher
    {
        return new ConfigPatcher();
    }

    /**
     * @return ConfigPatcher
     *
     * @covers ::__construct
     */
    public function testConstructorWithPatchers(): ConfigPatcher
    {
        return new ConfigPatcher(
            [$this->createMock(ConfigPatcherInterface::class)]
        );
    }

    /**
     * @return void
     *
     * @covers ::patch
     */
    public function testPatch()
    {
        $environment = $this->createMock(EnvironmentInterface::class);

        $patchers = [
            $this->createMock(ConfigPatcherInterface::class),
            $this->createMock(ConfigPatcherInterface::class),
        ];

        foreach ($patchers as $patcher) {
            $patcher
                ->expects($this->once())
                ->method('patch')
                ->with($environment);
        }

        (new ConfigPatcher($patchers))->patch($environment);
    }
}
