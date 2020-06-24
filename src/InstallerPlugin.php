<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 * @deprecated
 */

declare(strict_types=1);

namespace Mediact\CodingStandard\PhpStorm;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcher;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;

class InstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var ConfigPatcherInterface
     */
    private $patcher;

    /**
     * Constructor.
     *
     * @param ConfigPatcherInterface $patcher
     */
    public function __construct(ConfigPatcherInterface $patcher = null)
    {
        $this->patcher = $patcher !== null
            ? $patcher
            : new ConfigPatcher();
    }

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer    $composer
     * @param IOInterface $inputOutput
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $inputOutput): void
    {
    }

    /**
     * Get the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onNewCodeEvent',
            ScriptEvents::POST_UPDATE_CMD  => 'onNewCodeEvent'
        ];
    }

    /**
     * On new code.
     *
     * @param Event $event
     *
     * @return void
     */
    public function onNewCodeEvent(Event $event): void
    {
        $vendorDir   = $event->getComposer()->getConfig()->get('vendor-dir');
        $projectDir  = dirname($vendorDir);
        $phpStormDir = $projectDir . DIRECTORY_SEPARATOR . '.idea';
        $filesDir    = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'files';

        $phpStormDefaultPath = $this->getPhpStormDefaultPath();

        if (is_dir($phpStormDir) && is_dir($filesDir)) {
            $this->patcher->patch(
                new Environment(
                    new Filesystem($phpStormDir),
                    new Filesystem($phpStormDefaultPath),
                    new Filesystem($filesDir),
                    new Filesystem($projectDir),
                    $event->getIO(),
                    $event->getComposer()
                )
            );

            $output = $event->getIO();
            $output->write('Patched the PhpStorm config');
        }
    }

    /**
     * Get the latest version of clients phpstorm directory
     *
     * @return string
     */
    public function getPhpStormDefaultPath(): string
    {
        $phpStormDefaultPath = '';

        if (isset($_SERVER['HOME'])) {
            $home = $_SERVER['HOME'];
        } else {
            $home = getenv("HOME");
        }

        $phpStormDefaultPaths = array_reverse(glob("$home/.[pP]hp[sS]torm201*/config/"));
        if (! empty($phpStormDefaultPaths)) {
            $phpStormDefaultPath = reset($phpStormDefaultPaths);
        }

        return $phpStormDefaultPath;
    }
}
