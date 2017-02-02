<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace Mediact\CodingStandard\PhpStorm\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit_Framework_TestCase;
use Mediact\CodingStandard\PhpStorm\Filesystem;

/**
 * @coversDefaultClass \Mediact\CodingStandard\PhpStorm\Filesystem
 */
class FilesystemTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $vfs;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->vfs = vfsStream::setup(sha1(__FILE__));
    }

    /**
     * @return Filesystem
     * @covers ::__construct
     */
    public function testConstructor(): Filesystem
    {
        return new Filesystem($this->vfs->url());
    }

    /**
     * @depends testConstructor
     *
     * @param string     $path
     * @param string     $contents
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::read
     */
    public function testRead(
        string $path,
        string $contents,
        Filesystem $filesystem
    ) {
        $this->createFile($path, $contents);
        $this->assertEquals($contents, $filesystem->read($path));
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::read
     */
    public function testReadExceptionReadable(Filesystem $filesystem)
    {
        $this->createFile('foo.txt', 'foo', 0000);
        $filesystem->read('foo.txt');
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::read
     */
    public function testReadExceptionDir(Filesystem $filesystem)
    {
        $filesystem->read('');
    }

    /**
     * @depends testConstructor
     *
     * @param string     $path
     * @param string     $contents
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::has
     */
    public function testHas(
        string $path,
        string $contents,
        Filesystem $filesystem
    ) {
        $this->assertFalse($filesystem->has($path));
        $this->createFile($path, $contents);
        $this->assertTrue($filesystem->has($path));
    }

    /**
     * @depends testConstructor
     *
     * @param string     $path
     * @param string     $contents
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @dataProvider readWriteDataProvider
     *
     * @covers ::put
     */
    public function testPut(
        string $path,
        string $contents,
        Filesystem $filesystem
    ) {
        $filesystem->put($path, $contents);
        $this->assertEquals($contents, $this->readFile($path));
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::put
     */
    public function testPutExceptionWritableFile(
        Filesystem $filesystem
    ) {
        $this->createFile('path/to/foo.txt', 'foo', 0000);
        $filesystem->put('path/to/foo.txt', 'new_foo');
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::put
     */
    public function testPutExceptionWritableDirectory(
        Filesystem $filesystem
    ) {
        $this->createDir('path/to', 0000);
        $filesystem->put('path/to/foo.txt', 'new_foo');
    }

    /**
     * @return array
     */
    public function readWriteDataProvider(): array
    {
        return [
            [
                'path/to/file.txt',
                'some_contents'
            ]
        ];
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @covers ::createDir
     */
    public function testCreateDir(Filesystem $filesystem)
    {
        $this->assertFalse($this->dirExists('foo/bar'));
        $filesystem->createDir('foo/bar');
        $this->assertTrue($this->dirExists('foo/bar'));
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::createDir
     */
    public function testCreateDirExceptionFile(Filesystem $filesystem)
    {
        $this->createFile('foo/bar', 'foo');
        $filesystem->createDir('foo/bar');
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::createDir
     */
    public function testCreateDirExceptionWritable(Filesystem $filesystem)
    {
        $this->createDir('foo', 0000);
        $filesystem->createDir('foo/bar');
    }

    /**
     * @depends testConstructor
     *
     * @param array      $files
     * @param string     $path
     * @param array      $expected
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @dataProvider listFilesDataProvider
     *
     * @covers ::listFiles
     */
    public function testListFiles(
        array $files,
        string $path,
        array $expected,
        Filesystem $filesystem
    ) {
        foreach ($files as $file) {
            $this->createFile($file, '');
        }

        $this->assertEquals($expected, $filesystem->listFiles($path));
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @expectedException \RuntimeException
     *
     * @covers ::listFiles
     */
    public function testListFilesException(
        Filesystem $filesystem
    ) {
        $filesystem->listFiles('foo/bar');
    }

    /**
     * @return array
     */
    public function listFilesDataProvider(): array
    {
        return [
            [
                [
                    'foo.txt',
                    'foo/bar.txt',
                    'foo/bar/baz.txt',
                    'foo/bar/qux.txt',
                    'bar/baz.txt'
                ],
                'foo',
                [
                    'foo/bar.txt',
                    'foo/bar/baz.txt',
                    'foo/bar/qux.txt'
                ]
            ]
        ];
    }

    /**
     * @depends testConstructor
     *
     * @param Filesystem $filesystem
     *
     * @return void
     *
     * @covers ::getPath
     */
    public function testGetPath(Filesystem $filesystem)
    {
        $this->createFile('path/to/foo.txt', 'foo');
        $this->assertTrue($filesystem->has('///path//to/foo.txt'));
    }

    /**
     * @param string $path
     * @param string $contents
     * @param int    $chmod
     *
     * @return void
     */
    private function createFile(
        string $path,
        string $contents,
        int $chmod = 0777
    ) {
        $this->createDir(dirname($path));
        $url = $this->vfs->url() . '/' . $path;
        file_put_contents($url, $contents);
        chmod($url, $chmod);
    }

    /**
     * @param string $path
     * @param int    $chmod
     *
     * @return void
     */
    private function createDir(
        string $path,
        int $chmod = 0777
    ) {
        $url = $this->vfs->url() . '/' . $path;
        if (!file_exists($url)) {
            mkdir($url, 0777, true);
        }
        chmod($url, $chmod);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function readFile(string $path): string
    {
        $url = $this->vfs->url() . '/' . $path;
        return file_get_contents($url);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function dirExists(string $path): bool
    {
        $url = $this->vfs->url() . '/' . $path;
        return file_exists($url) && is_dir($url);
    }
}
