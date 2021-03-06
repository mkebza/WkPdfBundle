<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class PDFDocument
{
    public const INLINE = 'inline';
    public const ATTACHMENT = 'attachment';
    /**
     * @var string
     */
    private $content;

    /**
     * PdfDocument constructor.
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Gets raw content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get symfony response.
     *
     * @param string $filename
     * @param string $attachment
     *
     * @return Response
     */
    public function getResponse($filename = 'output.pdf', string $attachment = self::INLINE): Response
    {
        return new Response(
            $this->content,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('%s; filename="%s"', $attachment, $filename),
            ]
        );
    }

    /**
     * Writes content to file.
     *
     * @param string $filename
     */
    public function write(string $filename): void
    {
        (new Filesystem())->dumpFile($filename, $this->content);
    }
}
