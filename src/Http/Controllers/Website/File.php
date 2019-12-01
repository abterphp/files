<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Website;

use AbterPhp\Files\Service\File\Downloader as DownloadService;
use League\Flysystem\FileNotFoundException;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Http\Responses\StreamResponse;
use Opulence\Orm\OrmException;
use Opulence\Routing\Controller;

class File extends Controller
{
    /** @var DownloadService */
    protected $downloadService;

    /**
     * File constructor.
     *
     * @param DownloadService $downloadService
     */
    public function __construct(DownloadService $downloadService)
    {
        $this->downloadService = $downloadService;
    }

    /**
     * @param string $filesystemName
     *
     * @return Response
     */
    public function download(string $filesystemName): Response
    {
        try {
            $entity = $this->downloadService->getPublicFile($filesystemName);
            if (!$entity) {
                return new Response('', ResponseHeaders::HTTP_UNAUTHORIZED);
            }
            $streamCallable = $this->downloadService->getStream($entity);
        } catch (FileNotFoundException $e) {
            return new Response('', ResponseHeaders::HTTP_UNAUTHORIZED);
        } catch (OrmException $e) {
            return new Response('', ResponseHeaders::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), ResponseHeaders::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new StreamResponse(
            $streamCallable,
            ResponseHeaders::HTTP_OK,
            $this->getHeaders($entity->getPublicName())
        );
    }

    /**
     * @param string $filename
     *
     * @return array
     */
    protected function getHeaders(string $filename): array
    {
        return [
            'Content-type'              => 'application/octet-stream',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-disposition'       => sprintf('attachment; filename=%s', $filename),
        ];
    }
}
