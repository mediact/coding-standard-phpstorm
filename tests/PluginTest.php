<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\FilesystemInterface;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Plugin;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Plugin
 */
class PluginTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Plugin
     * @covers ::__construct
     */
    public function testConstructor(): Plugin
    {
        return new Plugin();
    }

    /**
     * @return Plugin
     * @covers ::__construct
     */
    public function testConstructorWithPatcher(): Plugin
    {
        return new Plugin(
            $this->createMock(ConfigPatcherInterface::class)
        );
    }

    /**
     * @depends testConstructor
     *
     * @param Plugin $plugin
     *
     * @return void
     *
     * @covers ::activate
     */
    public function testActivate(Plugin $plugin)
    {
        $plugin->activate(
            $this->createMock(Composer::class),
            $this->createMock(IOInterface::class)
        );
    }

    /**
     * @return array
     *
     * @covers ::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): array
    {
        return Plugin::getSubscribedEvents();
    }

    /**
     * @return void
     *
     * @covers ::onNewCodeEvent
     */
    public function testOnNewCodeEvent()
    {
        $vfs = vfsStream::setup(sha1(__FILE__));
        mkdir($vfs->url() . '/.idea');

        $config = $this->createMock(Config::class);
        $config
            ->expects($this->once())
            ->method('get')
            ->with('vendor-dir')
            ->willReturn($vfs->url() . '/vendor');

        $composer = $this->createMock(Composer::class);
        $composer
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn($config);

        $output = $this->createMock(IOInterface::class);
        $output
            ->expects($this->once())
            ->method('isVerbose')
            ->willReturn(true);

        $output
            ->expects($this->once())
            ->method('write')
            ->with($this->isType('string'));

        $event = $this->createMock(Event::class);
        $event
            ->expects($this->exactly(2))
            ->method('getComposer')
            ->willReturn($composer);

        $event
            ->expects($this->exactly(2))
            ->method('getIO')
            ->willReturn($output);

        $patcher = $this->createMock(ConfigPatcherInterface::class);
        $patcher
            ->expects($this->once())
            ->method('patch')
            ->with($this->isInstanceOf(EnvironmentInterface::class));

        $plugin = new Plugin($patcher);
        $plugin->onNewCodeEvent($event);
    }
}
