<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

class Filesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    private $root;

    /**
     * Constructor.
     *
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path)
    {
        return file_exists($this->getPath($path));
    }

    /**
     * Read a path.
     *
     * @param string $path
     *
     * @return string
     *
     * @throws RuntimeException When the path is not readable.
     */
    public function read($path)
    {
        $path = $this->getPath($path);
        if (!is_readable($path) || !is_file($path)) {
            throw new RuntimeException($path . ' is not readable');
        }
        return file_get_contents($path);
    }

    /**
     * Write contents to a path.
     *
     * @param string $path
     * @param string $contents
     *
     * @return bool
     *
     * @throws RuntimeException When the path is not writable.
     */
    public function put($path, $contents)
    {
        $directory = dirname($path);
        $this->createDir($directory);
        $path = $this->getPath($path);
        if (!is_writable($path) && !is_writable(dirname($path))) {
            throw new RuntimeException($path . ' is not writable');
        }
        return file_put_contents($path, $contents);
    }

    /**
     * Create a directory if it does not exist.
     *
     * @param string $path
     *
     * @return bool
     *
     * @throws RuntimeException When the directory can not be created.
     */
    public function createDir($path)
    {
        $directory = $this->getPath($path);
        if (!is_dir($directory)) {
            if (file_exists($directory)) {
                throw new RuntimeException(
                    $directory . ' is not a directory.'
                );
            }
            if (!mkdir($directory, 0777, true)) {
                throw new RuntimeException(
                    $directory . ' can not be created.'
                );
            }
        }
        return true;
    }

    /**
     * List contents of a directory.
     *
     * @param string $path
     *
     * @return array
     *
     * @throws RuntimeException When the path is not a directory.
     */
    public function listContents($path = '')
    {
        $directory = $this->getPath($path);
        if (!is_dir($directory)) {
            throw new RuntimeException(
                $directory . ' is not a directory.'
            );
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->getPath($path),
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $files = [];
        /** @var SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                continue;
            }
            $files[] = str_replace($this->root, '', $fileInfo->getPathname());
        }

        return $files;
    }

    /**
     * Get the full path.
     *
     * @param string $path
     *
     * @return string
     */
    private function getPath($path)
    {
        return $this->root . $path;
    }
}
