<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

use MKebza\WkPDF\Exception\PDFRenderingProfileNotFoundException;

class RenderingProfileRegistry
{
    private $profiles = [];

    public function add(string $name, PDFRenderingProfile $profile): void
    {
        $this->profiles[$name] = $profile;
    }

    public function addFromArray(string $name, array $profile): void
    {
        $this->profiles[$name] = new PDFRenderingProfile($profile);
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->profiles);
    }

    public function get(string $name): PDFRenderingProfile
    {
        if (!$this->has($name)) {
            throw new PDFRenderingProfileNotFoundException(sprintf("Requested PDF Rendering profile '%s' is not registered", $name));
        }

        return $this->profiles[$name];
    }
}
