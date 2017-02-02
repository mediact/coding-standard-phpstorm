<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm;

use InvalidArgumentException;
use SimpleXMLElement;

interface XmlAccessorInterface
{
    /**
     * Get a child node, create it when it does not exist.
     *
     * @param SimpleXMLElement $element
     * @param string           $name
     * @param array            $attributes
     *
     * @return SimpleXMLElement
     */
    public function getChild(
        SimpleXMLElement $element,
        $name,
        array $attributes = []
    );

    /**
     * Get a descendant, create it when it does not exist.
     *
     * @param SimpleXMLElement $element
     * @param array            $path
     *
     * @return SimpleXMLElement
     *
     * @throws InvalidArgumentException When the descendant path is invalid.
     */
    public function getDescendant(
        SimpleXMLElement $element,
        array $path
    );

    /**
     * Set the attributes of a node.
     *
     * @param SimpleXMLElement $element
     * @param array            $attributes
     *
     * @return void
     */
    public function setAttributes(
        SimpleXMLElement $element,
        array $attributes
    );
}
