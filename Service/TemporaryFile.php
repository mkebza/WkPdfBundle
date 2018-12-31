<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class TemporaryFile
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * TemporaryFile constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->fs = new Filesystem();
    }

    public function __toString()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function write(string $data): void
    {
        $this->fs->dumpFile($this->path, $data);
    }

    public function destroy(): void
    {
        $this->fs->remove($this->path);
    }

    public function read(): string
    {
        $content = file_get_contents($this->path);
        if (false === $content) {
            throw new IOException(sprintf("Can't read file '%s'", $this->path));
        }

        return $content;
    }
}
