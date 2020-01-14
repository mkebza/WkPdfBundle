<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\WkPDF\Service;

use MKebza\WkPDF\Exception\PDFRenderException;
use Symfony\Component\Process\Process;

class PDFRenderer
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var TemporaryFileManager
     */
    private $tmpFileManager;

    /**
     * @var RenderingProfileNormalizer
     */
    private $profileNormalizer;

    /**
     * PDFRenderer constructor.
     *
     * @param string                     $bin
     * @param TemporaryFileManager       $tmpFileManager
     * @param RenderingProfileNormalizer $profileNormalizer
     */
    public function __construct(
        string $bin,
        TemporaryFileManager $tmpFileManager,
        RenderingProfileNormalizer $profileNormalizer
    ) {
        $this->bin = $bin;
        $this->tmpFileManager = $tmpFileManager;
        $this->profileNormalizer = $profileNormalizer;
    }

    /**
     * @param string $html    HTML content
     * @param null   $profile Custom profle, can be profile name, array of options (new anonymous profile will be created), PDFRenderingProfile object or null (default profile is used)
     *
     * @return PDFDocument
     */
    public function fromHtml(string $html, $profile = null): PDFDocument
    {
        $tmpFile = $this->tmpFileManager->create('html');
        $tmpFile->write($html);

        $document = $this->fromFile($tmpFile->getPath(), $profile);
        $tmpFile->destroy();

        return $document;
    }

    /**
     * @param string $file    Filename
     * @param null   $profile Custom profle, can be profile name, array of options (new anonymous profile will be created), PDFRenderingProfile object or null (default profile is used)
     *
     * @throws PDFRenderException
     *
     * @return PDFDocument
     */
    public function fromFile(string $file, $profile = null): PDFDocument
    {
        $profile = $this->profileNormalizer->normalize($profile);
        $generated = $this->tmpFileManager->create('pdf');

        $command = Process::fromShellCommandline(
            sprintf('%s %s %s %s',
                $this->bin,
                implode(' ', $profile->getOptions()),
                escapeshellarg($file),
                escapeshellarg($generated->getPath())
            )
        );
        $command->run();

        if (!$command->isSuccessful()) {
            throw new PDFRenderException(sprintf(
                "Error while rendering PDF, wkhtmltopdf command \n\n%s \n\nOutput: \n\n%s",
                $command->getCommandLine(), $command->getErrorOutput()
            ));
        }

        $document = $generated->read();
        $generated->destroy();

        return new PDFDocument($document);
    }
}
