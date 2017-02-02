<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Mediact\CodingStandard\PhpStorm;

interface FilesystemInterface
{
    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path);

    /**
     * Read a path.
     *
     * @param string $path
     *
     * @return string
     */
    public function read($path);

    /**
     * Write contents to a path.
     *
     * @param string $path
     * @param string $contents
     *
     * @return bool
     */
    public function put($path, $contents);

    /**
     * Create a directory if it does not exist.
     *
     * @param string $path
     *
     * @return bool
     */
    public function createDir($path);

    /**
     * List contents of a directory.
     *
     * @param string $path
     *
     * @return array
     */
    public function listFiles($path = '');
}
