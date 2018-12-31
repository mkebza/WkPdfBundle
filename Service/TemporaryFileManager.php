<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

class TemporaryFileManager
{
    /**
     * @var string
     */
    private $tmp;

    /**
     * TempFileManager constructor.
     *
     * @param string $tmp
     */
    public function __construct(string $tmp)
    {
        $this->tmp = $tmp;
    }

    public function create(string $extension): TemporaryFile
    {
        return new TemporaryFile(
            sprintf(sprintf('%s/mkebza_wk_pdf_%s.%s', $this->tmp, uniqid('', true), $extension))
        );
    }

//    public function destroy(string $filename): void {
//
//    }
}
