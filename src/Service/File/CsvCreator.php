<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\File;

use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Framework\Filesystem\Uploader;

class CsvCreator
{
    const DOUBLE_QUOTE = '"';
    const SINGLE_QUOTE = "'";

    const IN_CHARSET   = 'UTF-8';
    const OUT_CHARSET  = 'UTF-8';
    const OUT_FILENAME = 'files.txt';

    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var Uploader */
    protected $uploader;

    /**
     * Csv constructor.
     *
     * @param Uploader $uploader
     */
    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param Entity[] $files
     *
     * @return string
     */
    public function csv(array $files): string
    {
        $lines = [];
        foreach ($files as $file) {
            $uploadedAt = $file->getUploadedAt()->format(static::DATE_FORMAT);
            $path       = $this->uploader->getPath(Uploader::DEFAULT_KEY, $file->getFilesystemName());
            $fileSize   = $this->uploader->getSize($path);

            $lines[] = sprintf(
                '%s,%s,%s,%s,%s',
                (string)$file->getId(),
                $this->csvText($uploadedAt),
                $this->csvText($file->getPublicName()),
                (string)$fileSize,
                $this->csvText($file->getDescription())
            );
        }

        $content = implode(PHP_EOL, $lines);

        return $content;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function csvText(string $string)
    {
        $string = str_replace(static::DOUBLE_QUOTE, static::SINGLE_QUOTE, $string);

        return static::DOUBLE_QUOTE . trim($string, static::SINGLE_QUOTE) . static::DOUBLE_QUOTE;
    }
}
