<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

class RenderingProfileNormalizer
{
    /**
     * @var RenderingProfileRegistry
     */
    private $registry;

    /**
     * RenderingProfileNormalizer constructor.
     *
     * @param RenderingProfileRegistry $registry
     */
    public function __construct(RenderingProfileRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function normalize($profile): PDFRenderingProfile
    {
        if ($profile instanceof PDFRenderingProfile) {
            return $profile;
        }

        if (null === $profile) {
            return 'Default';
        }

        if (is_string($profile)) {
            return $this->registry->get($profile);
        }

        if (is_array($profile)) {
            return new PDFRenderingProfile($profile);
        }

        throw new \LogicException('Undefined type of PDF rendering profile, you have to provide object, string, null or array of parameters');
    }
}
