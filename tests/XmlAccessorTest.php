<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests;

use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\XmlAccessor;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\XmlAccessor
 */
class XmlAccessorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $input
     * @param string $name
     * @param array  $attributes
     * @param array  $extraAttributes
     * @param string $expected
     *
     * @return void
     *
     * @dataProvider getChildDataProvider
     *
     * @covers ::getChild
     * @covers ::getAttributesXpath
     */
    public function testGetChild(
        string $input,
        string $name,
        array $attributes,
        array $extraAttributes,
        string $expected
    ) {
        $accessor = new XmlAccessor();

        $xml = simplexml_load_string($input);

        $child = $accessor->getChild($xml, $name, $attributes);
        foreach ($extraAttributes as $key => $value) {
            $child->addAttribute($key, $value);
        }
        $this->assertEquals(trim($expected), trim($xml->asXML()));
    }

    /**
     * @return array
     */
    public function getChildDataProvider(): array
    {
        return [
            [
                '<?xml version="1.0"?>
<some_data></some_data>',
                'foo',
                ['bar' => 'bar_value', 'baz' => 'baz_value'],
                ['qux' => 'qux_value'],
                '<?xml version="1.0"?>
<some_data><foo bar="bar_value" baz="baz_value" qux="qux_value"/></some_data>'
            ],
            [
                '<?xml version="1.0"?>
<some_data><foo bar="bar_value" baz="baz_value"/></some_data>',
                'foo',
                ['bar' => 'bar_value', 'baz' => 'baz_value'],
                ['qux' => 'qux_value'],
                '<?xml version="1.0"?>
<some_data><foo bar="bar_value" baz="baz_value" qux="qux_value"/></some_data>'
            ]
        ];
    }

    /**
     * @param string $input
     * @param array  $path
     * @param array  $extraAttributes
     * @param string $expected
     *
     * @return void
     *
     * @dataProvider getDescendantDataProvider
     *
     * @covers ::getDescendant
     */
    public function testGetDescendant(
        string $input,
        array $path,
        array $extraAttributes,
        string $expected
    ) {
        $accessor = new XmlAccessor();

        $xml = simplexml_load_string($input);

        $child = $accessor->getDescendant($xml, $path);
        foreach ($extraAttributes as $key => $value) {
            $child->addAttribute($key, $value);
        }
        $this->assertEquals(trim($expected), trim($xml->asXML()));
    }

    /**
     * @return void
     *
     * @dataProvider getDescendantDataProvider
     *
     * @expectedException \InvalidArgumentException
     *
     * @covers ::getDescendant
     */
    public function testGetDescendantException()
    {
        $accessor = new XmlAccessor();

        $xml = simplexml_load_string('<some_data></some_data>');
        $accessor->getDescendant($xml, [[]]);
    }

    /**
     * @return array
     */
    public function getDescendantDataProvider(): array
    {
        return [
            [
                '<?xml version="1.0"?>
<some_data></some_data>',
                [
                    ['foo', ['bar' => 'bar_value']],
                    ['baz']
                ],
                ['qux' => 'qux_value'],
                '<?xml version="1.0"?>
<some_data><foo bar="bar_value"><baz qux="qux_value"/></foo></some_data>'
            ]
        ];
    }

    /**
     * @param string $input
     * @param array  $attributes
     * @param string $expected
     *
     * @dataProvider setAttributesDataProvider
     *
     * @return void
     *
     * @covers ::setAttributes
     */
    public function testSetAttributes(
        string $input,
        array $attributes,
        string $expected
    ) {
        $accessor = new XmlAccessor();

        $xml = simplexml_load_string($input);
        $accessor->setAttributes($xml, $attributes);
        $this->assertEquals(trim($expected), trim($xml->asXML()));
    }

    /**
     * @return array
     */
    public function setAttributesDataProvider(): array
    {
        return [
            [
                '<?xml version="1.0"?>
<foo bar="bar_value" baz="baz_value"/>',
                ['baz' => 'new_baz_value', 'qux' => 'qux_value'],
                '<?xml version="1.0"?>
<foo bar="bar_value" baz="new_baz_value" qux="qux_value"/>'
            ]
        ];
    }
}
