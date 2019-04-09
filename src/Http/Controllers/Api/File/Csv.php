<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api\File;

use AbterPhp\Files\Service\File\CsvCreator;
use AbterPhp\Files\Orm\FileRepo as Repo;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;

class Csv extends Controller
{
    const IN_CHARSET   = 'UTF-8';
    const OUT_CHARSET  = 'UTF-8';

    const OUT_FILENAME = 'files.txt';

    /** @var Repo */
    protected $repo;

    /** @var CsvCreator */
    protected $csvCreator;

    /**
     * Csv constructor.
     *
     * @param Repo       $repo
     * @param CsvCreator $csvService
     */
    public function __construct(Repo $repo, CsvCreator $csvService)
    {
        $this->repo       = $repo;
        $this->csvCreator = $csvService;
    }

    /**
     * @return Response
     */
    public function csv(): Response
    {
        $files = $this->repo->getAll();

        $content = $this->csvCreator->csv($files);
        $content = $this->convertContent($content);

        $headers = $this->getHeaders(strlen($content));

        return new Response(
            $content,
            ResponseHeaders::HTTP_OK,
            $headers
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function convertContent(string $content): string
    {
        if (static::IN_CHARSET !== static::OUT_CHARSET) {
            return iconv(static::IN_CHARSET, static::OUT_CHARSET, $content);
        }

        return $content;
    }

    /**
     * @param int $contentLength
     *
     * @return array
     */
    protected function getHeaders(int $contentLength): array
    {
        return [
            'Expires'             => '0',
            'Cache-Control'       => 'private',
            'Pragma'              => 'cache',
            'Content-type'        => sprintf('text/csv, %s', static::OUT_CHARSET),
            'Content-Disposition' => sprintf('attachment; filename="%s"', static::OUT_FILENAME),
            'Content-Length'      => $contentLength,
        ];
    }
}
