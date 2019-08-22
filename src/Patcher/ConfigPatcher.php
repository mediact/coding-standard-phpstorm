<?php declare(strict_types=1);
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm\Patcher;

use Mediact\CodingStandard\PhpStorm\EnvironmentInterface;
use Mediact\CodingStandard\PhpStorm\XmlAccessor;

class ConfigPatcher implements ConfigPatcherInterface
{
    /**
     * @var ConfigPatcherInterface[]
     */
    private $patchers;

    /**
     * Constructor.
     *
     * @param array $patchers
     */
    public function __construct(array $patchers = null)
    {
        $xmlAccessor = new XmlAccessor();

        $this->patchers = $patchers !== null
            ? $patchers
            : [
                new CodeStylePatcher(),
                new FileTemplatesPatcher($xmlAccessor),
                new InspectionsPatcher($xmlAccessor),
                new TemplateSettingsPatcher($xmlAccessor),
                new LiveTemplatesPatcher()
            ];
    }

    /**
     * Patch the config.
     *
     * @param EnvironmentInterface $environment
     *
     * @return void
     */
    public function patch(
        EnvironmentInterface $environment
    ): void {
        foreach ($this->patchers as $patcher) {
            $patcher->patch($environment);
        }
    }
}
