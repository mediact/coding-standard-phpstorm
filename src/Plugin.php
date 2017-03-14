<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcher;
use Mediact\CodingStandard\PhpStorm\Patcher\ConfigPatcherInterface;

class Plugin implements PluginInterface, EventSubscriberInterface
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
    public function activate(Composer $composer, IOInterface $inputOutput)
    {
    }

    /**
     * Get the subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
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
    public function onNewCodeEvent(Event $event)
    {
        $vendorDir   = $event->getComposer()->getConfig()->get('vendor-dir');
        $projectDir  = dirname($vendorDir);
        $phpStormDir = $projectDir . DIRECTORY_SEPARATOR . '.idea';
        $filesDir    = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'files';

        if (is_dir($phpStormDir) && is_dir($filesDir)) {
            $this->patcher->patch(
                new Environment(
                    new Filesystem($phpStormDir),
                    new Filesystem($filesDir),
                    new Filesystem($projectDir),
                    $event->getIO(),
                    $event->getComposer()
                )
            );

            $output = $event->getIO();
            if ($output->isVerbose()) {
                $output->write('Patched the PhpStorm config');
            }
        }
    }
}
